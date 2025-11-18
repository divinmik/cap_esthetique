<?php
// app/Livewire/Prof/Manager.php
namespace App\Livewire\Prof;

use App\Models\User;
use App\Models\Teacher;
use Livewire\Component;
use App\Models\CourseModule;
use App\Models\ModuleContent;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class Manager extends Component
{
    use WithFileUploads;

    public ?int $editingContentId = null;
    public string $editingContentTitle = '';

    public string $code;
    public ?User $teacher = null;

    // UI
    public string $tab = 'modules'; // modules | profile | password

    // Profil (édition)
    public $firstname,$lastname,$email,$phone,$birthdate,$birthplace,$profile_photo;

    // Password
    public $old_password,$password,$password_confirmation;

    // Module form
    public ?int $editingModuleId = null;
    public string $module_title = '';
    public string $module_description = '';

    // Content form
    public ?int $moduleIdForContent = null;
    public string $content_title = '';
    public string $content_type = 'pdf'; // video_url|pdf|image|audio|file
    public ?string $content_url = null;  // pour vidéo
    public $content_file;                // pour fichiers

    protected function rules(): array {
        return [
            'module_title' => ['required','string','max:255'],
            'module_description' => ['nullable','string','max:2000'],
        ];
    }

    public function mount(string $code): void {
        $this->code = $code;
        $this->teacher = User::where('code',$code)->firstOrFail();

        // hydrate profil
        $this->firstname = $this->teacher->firstname;
        $this->lastname  = $this->teacher->lastname;
        $this->email     = $this->teacher->email;
        $this->phone     = $this->teacher->phone;
        $this->birthdate = optional($this->teacher->birthdate)->format('Y-m-d');
        $this->birthplace= $this->teacher->birthplace;
    }

    public function setTab(string $tab): void {
        $this->tab = $tab;
    }

    /* ---------- Profil ---------- */
    public function saveProfile(): void {
        $data = $this->validate([
            'firstname' => ['required','string','max:100'],
            'lastname'  => ['required','string','max:100'],
            'email'     => ['required','email', Rule::unique('teachers','email')->ignore($this->teacher->id)],
            'phone'     => ['nullable','string','max:50'],
            'birthdate' => ['nullable','date'],
            'birthplace'=> ['nullable','string','max:150'],
            'profile_photo' => ['nullable','image','max:2048'], // 2MB
        ]);

        if ($this->profile_photo) {
            $path = $this->profile_photo->store('profiles','public');
            $this->teacher->profile_photo_path = 'profiles/'.basename($path);
        }

        $this->teacher->fill([
            'firstname' => $this->firstname,
            'lastname'  => $this->lastname,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'birthdate' => $this->birthdate ?: null,
            'birthplace'=> $this->birthplace,
        ])->save();

        session()->flash('status','Profil mis à jour.');
    }

    /* ---------- Mot de passe ---------- */
    public function savePassword(): void {
        $this->validate([
            'old_password' => ['required'],
            'password'     => ['required','min:8','confirmed'],
        ]);

        if (! Hash::check($this->old_password, $this->teacher->password)) {
            $this->addError('old_password','Ancien mot de passe incorrect.');
            return;
        }

        $this->teacher->password = Hash::make($this->password);
        $this->teacher->save();

        $this->reset(['old_password','password','password_confirmation']);
        session()->flash('status','Mot de passe modifié.');
    }

    /* ---------- Modules ---------- */
    public function saveModule(): void {
        $this->validate();

        if ($this->editingModuleId) {
            $module = CourseModule::where('user_id',$this->teacher->id)->findOrFail($this->editingModuleId);
            $module->update([
                'title' => $this->module_title,
                'description' => $this->module_description,
            ]);
            session()->flash('status','Module mis à jour.');
        } else {
            CourseModule::create([
                'user_id' => $this->teacher->id,
                'title' => $this->module_title,
                'description' => $this->module_description,
            ]);
            session()->flash('status','Module créé.');
        }

        $this->resetModuleForm();
    }

    public function editModule(int $id): void {
        $m = CourseModule::where('user_id',$this->teacher->id)->findOrFail($id);
        $this->editingModuleId = $m->id;
        $this->module_title = $m->title;
        $this->module_description = $m->description ?? '';
    }

    public function deleteModule(int $id): void {
        $m = CourseModule::where('user_id',$this->teacher->id)->findOrFail($id);
        foreach ($m->contents as $c) {
            if ($c->path) Storage::disk('public')->delete($c->path);
        }
        $m->delete();
        session()->flash('status','Module supprimé.');
        $this->resetModuleForm();
    }

    public function resetModuleForm(): void {
        $this->editingModuleId = null;
        $this->module_title = '';
        $this->module_description = '';
    }

    /* ---------- Contenus ---------- */
    public function setModuleForContent(int $moduleId): void {
        $this->moduleIdForContent = $moduleId;
        $this->resetContentForm();
    }

    public function saveContent(): void {
        $this->validate([
        'moduleIdForContent' => ['required','integer','exists:course_modules,id'],
        'content_title' => ['required','string','max:255'],
        'content_type'  => ['required','in:video_url,video_file,pdf,image,audio,file'],
        'content_url'   => [Rule::requiredIf(fn()=> $this->content_type==='video_url'),'nullable','url','max:204800'],
        'content_file'  => [Rule::requiredIf(fn()=> in_array($this->content_type,['video_file','pdf','image','audio','file'])),
                            'nullable','file',
                            // 💡 Limites/MIME : ajuste à tes besoins/serveur
                            'max:512000', // ~500MB
                            'mimetypes:video/mp4,video/webm,video/ogg,video/quicktime,application/pdf,image/jpeg,image/png,image/gif,audio/mpeg,audio/mp3,audio/ogg,application/zip,application/octet-stream'
        ],
        ]);

        $module = CourseModule::where('user_id',$this->teacher->id)->findOrFail($this->moduleIdForContent);

        $payload = [
            'course_module_id' => $module->id,
            'title' => $this->content_title,
            'type'  => $this->content_type,
        ];

        if ($this->content_type === 'video_url') {
            $payload['url'] = $this->content_url;
            $payload['size_bytes'] = 0;
        } else {
            $dir = match ($this->content_type) {
                'video_file' => 'modules/videos',
                'pdf'        => 'modules/pdf',
                'image'      => 'modules/images',
                'audio'      => 'modules/audio',
                default      => 'modules/files',
            };

            $path = $this->content_file->store($dir,'public');
            $payload['path'] = $path;
            $payload['size_bytes'] = $this->content_file->getSize();
        }

        ModuleContent::create($payload);
        $module->increment('contents_count');

        session()->flash('status','Contenu ajouté.');
        $this->resetContentForm();
    }


    public function deleteContent(int $contentId): void {
        $content = ModuleContent::whereHas('module', function($q){
            $q->where('user_id',$this->teacher->id);
        })->findOrFail($contentId);

        if ($content->path) Storage::disk('public')->delete($content->path);

        $content->module()->decrement('contents_count');
        $content->delete();

        session()->flash('status','Contenu supprimé.');
    }

    public function resetContentForm(): void {
        $this->content_title = '';
        $this->content_type = 'pdf';
        $this->content_url = null;
        $this->content_file = null;
    }

    public function getModulesProperty() {
        return $this->teacher->modules()->withCount('contents')->latest()->get();
    }

    public function startEditContentTitle(int $contentId): void
    {
        // Vérifie que le contenu appartient bien à un de tes modules
        $content = \App\Models\ModuleContent::query()
            ->where('id', $contentId)
            ->whereHas('module', fn($q) => $q->where('user_id', $this->teacher->id)) // adapte si besoin
            ->firstOrFail();

        $this->editingContentId = $content->id;
        $this->editingContentTitle = $content->title ?? '';
    }

    public function cancelEditContentTitle(): void
    {
        $this->reset(['editingContentId', 'editingContentTitle']);
        $this->resetErrorBag('editingContentTitle');
    }

    public function saveContentTitle(): void
    {
        $this->validate([
            'editingContentTitle' => 'required|string|min:2|max:255',
        ], [], [
            'editingContentTitle' => 'titre',
        ]);

        abort_if(is_null($this->editingContentId), 400, 'Aucun contenu sélectionné.');

        $content = \App\Models\ModuleContent::query()
            ->where('id', $this->editingContentId)
            ->whereHas('module', fn($q) => $q->where('user_id', $this->teacher->id)) // adapte si besoin
            ->firstOrFail();

        $content->title = $this->editingContentTitle;
        $content->save();

        // Remets l’UI propre
        $this->cancelEditContentTitle();

        // Recharge la liste (si $modules vient d’une requête live)
        //$this->loadModules?->call(); // si tu as une méthode ; sinon redemande les données
        // ou: $this->modules = $this->teacher->modules()->withCount('contents')->with('contents')->get();

        session()->flash('status', 'Titre du contenu mis à jour.');
    }


    public function render() {
        return view('livewire.prof.manager', [
            'modules' => $this->modules,
        ]);
    }
}

