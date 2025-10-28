<div>
 <form wire:submit.prevent="submit" class="bg-white/95 rounded-2xl p-4 sm:p-6 md:p-8 shadow-2xl" novalidate>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <!-- Nom -->
    <div>
      <label for="lastname" class="block text-sm font-medium text-gray-700">Nom <span class="text-red-600">*</span></label>
      <input id="lastname" wire:model.defer="lastname" type="text" required
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
             placeholder="Nom" />
      @error('lastname') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Prénom -->
    <div>
      <label for="firstname" class="block text-sm font-medium text-gray-700">Prénom <span class="text-red-600">*</span></label>
      <input id="firstname" wire:model.defer="firstname" type="text" required
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
             placeholder="Prénom" />
      @error('firstname') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Date -->
    <div>
      <label for="birthdate" class="block text-sm font-medium text-gray-700">Date de naissance <span class="text-red-600">*</span></label>
      <input id="birthdate" wire:model.defer="birthdate" type="date"
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2" />
      @error('birthdate') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Lieu -->
    <div>
      <label for="birthplace" class="block text-sm font-medium text-gray-700">Lieu de naissance <span class="text-red-600">*</span></label>
      <input id="birthplace" wire:model.defer="birthplace" type="text"
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
             placeholder="Brazzaville" />
      @error('birthplace') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Téléphone -->
    <div>
      <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone <span class="text-red-600">*</span></label>
      <input id="phone" wire:model.defer="phone" type="tel" required
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
             placeholder="+242 06 123 45 67" />
      @error('phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- E-mail -->
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
      <input id="email" wire:model.defer="email" type="email"
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
             placeholder="exemple@domaine.com" />
      @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>
  </div>

  <div class="mt-4">
        <label for="message" class="block text-sm font-medium text-gray-700">Adresse <span class="text-red-600">*</span> </label>
        <textarea id="message" wire:model="address" rows="4"
                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
                placeholder="Votre adresse..."></textarea>
        @error('address') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

  <!-- Ville choisie -->
  <fieldset class="mt-4">
    <legend class="text-sm font-medium text-gray-700">Ville choisie <span class="text-red-600">*</span></legend>
    <select id="city" wire:model.defer="city"
            class="mt-2 block w-full rounded-lg border-gray-200 px-3 py-2 focus:ring-2 focus:ring-pink-300">
      <option value="">-- Choisir --</option>
      @foreach(['Brazzaville','Pointe-Noire','Ouesso','Dolisie','Nkayi','Oyo','Owando'] as $c)
        <option value="{{ $c }}">{{ $c }}</option>
      @endforeach
    </select>
    @error('city') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
  </fieldset>

  <!-- Niveau -->
  <fieldset class="mt-4">
    <legend class="text-sm font-medium text-gray-700">Niveau scolaire atteint <span class="text-red-600">*</span></legend>
    <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:gap-6 gap-3 text-sm">
      <label class="inline-flex items-center gap-2">
        <input type="radio" wire:model.live="level" name="level" value="CEP" class="form-radio" required> CEP
      </label>
      <label class="inline-flex items-center gap-2">
        <input type="radio" wire:model.live="level" name="level" value="BEPC" class="form-radio"> BEPC
      </label>
      <label class="inline-flex items-center gap-2">
        <input type="radio" wire:model.live="level" name="level" value="BAC" class="form-radio"> BAC
      </label>
      <div class="flex items-center gap-2">
        <label class="inline-flex items-center gap-2">
          <input type="radio" wire:model.live="level" name="level" value="Autre" class="form-radio"> Autre
        </label>
        @if ($level == "Autre")
          <input type="text" wire:model.defer="level_other" id="level_other"
                 class="mt-1 block rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2 w-full sm:w-64"
                 placeholder="Précisez" />
        @endif
      </div>
    @error('level') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
  </fieldset>

  <!-- Niveau -->
  <fieldset class="mt-4">
    <legend class="text-sm font-medium text-gray-700">Souhaitez-vous être mis(e) en contact avec un salon partenaire ?
 <span class="text-red-600">*</span></legend>
    <div class="mt-2 flex flex-col sm:flex-row sm:items-center sm:gap-6 gap-3 text-sm">
      <label class="inline-flex items-center gap-2">
        <input type="radio" wire:model="contact_salon" name="contact_salon" value="Oui" class="form-radio" required> Oui
      </label>
      <label class="inline-flex items-center gap-2">
        <input type="radio" wire:model="contact_salon" name="contact_salon" value="Non" class="form-radio"> Non
      </label>
    </div>
    @error('contact_salon') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
  </fieldset>

  <!-- Type formation -->
  <fieldset class="mt-4">
    <legend class="text-sm font-medium text-gray-700">Type formation <span class="text-red-600">*</span></legend>
    <select id="type_formation" wire:model.defer="type_formation"
            class="mt-2 block w-full rounded-lg border-gray-200 px-3 py-2 focus:ring-2 focus:ring-pink-300">
      <option value="">-- Choisir --</option>
      @foreach($formations as $c)
        <option value="{{ $c }}">{{ $c }}</option>
      @endforeach
    </select>
    @error('type_formation') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
  </fieldset>


  <!-- Photo profil -->
  <div class="mt-4">
    <label class="block text-sm font-medium text-gray-700">Photo de profil du candidat</label>
    <div class="mt-2 bg-gray-50 rounded-md p-3 border border-dashed border-gray-200">
      <label class="block text-sm text-gray-600">Téléverser une image</label>
      <input id="profile_photo_path" wire:model="profile_photo_path" name="profile_photo_path" type="file" accept="image/*"
             class="mt-1 block w-full text-sm text-gray-700" />
      <div wire:loading class="text-green-600 text-sm" wire:target="profile_photo_path">Téléchargement...</div>
      @error('profile_photo_path') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

      @if ($profile_photo_path)
        <div class="mt-2">
          <img src="{{ $profile_photo_path->temporaryUrl() }}" alt="Preview" class="rounded-md" style="max-width: 120px;">
        </div>
      @endif
    </div>
  </div>

  <!-- Consentement -->
  <div class="mt-6">
    <label class="inline-flex items-start gap-2 text-sm">
      <input type="checkbox" wire:model="consent" name="consent" id="consent" required class="form-checkbox mt-1">
      <span>J'atteste l'exactitude des informations fournies et accepte le traitement de mes données.</span>
    </label>
    @error('consent') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
  </div>

  <!-- Buttons -->
  <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:gap-3 gap-3">
    <button
      type="submit"
      wire:loading.attr="disabled"
      class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-full bg-[var(--primary-pink)] text-white font-medium shadow hover:opacity-95 focus:outline-none focus:ring-4 focus:ring-pink-200"
    >
      <span>S'inscrire</span>
      <svg wire:loading wire:target="submit" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
    </button>

    <button type="button" id="formReset" wire:click="resets" class="inline-flex items-center justify-center px-4 py-2 rounded-full border border-rose-200 bg-white text-rose-400 hover:bg-rose-50">
      Réinitialiser
    </button>
  </div>
</form>


</div>
