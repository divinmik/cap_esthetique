<div class="w-full overflow-x-auto">
  {{-- @if (session()->has('message'))
  <div x-data="{ show: true }" x-show="show" x-init="setTimeout(()=>show=false,4000)"
       class="fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded shadow">
    {{ session('message') }}
  </div>
@endif --}}
@if(session()->has('message') || session()->has('status'))
  <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)" x-show="show"
       x-transition
       class="fixed right-4 top-4 z-50">
    <div
      class="flex items-start space-x-3 max-w-sm w-full rounded-2xl shadow-lg px-4 py-3 ring-1 ring-black/5"
      :class="{
        'bg-green-600 text-white': '{{ session('status', 'success') }}' === 'success',
        'bg-red-600 text-white': '{{ session('status', 'success') }}' === 'error'
      }"
      role="status"
      aria-live="polite"
    >
      <!-- Icône -->
      <template x-if="'{{ session('status', 'success') }}' === 'success'">
        <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </template>
      <template x-if="'{{ session('status', 'success') }}' === 'error'">
        <svg class="w-6 h-6 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </template>

      <!-- Texte -->
      <div class="flex-1 text-sm font-medium leading-tight">
        {{ session('message') }}
      </div>

      <!-- Bouton fermer -->
      <button @click="show = false" aria-label="Fermer" class="ml-2 inline-flex p-1 rounded-full focus:outline-none focus:ring-2 focus:ring-white/30">
        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
@endif

  @if ($statutPassword == true)
    <table class="min-w-[1000px] w-full divide-y divide-gray-200 rounded-lg shadow">
    <thead class="bg-gray-50">
      <tr>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700 cursor-pointer" wire:click="sortBy('id')">#</th>

        <!-- Nouvelle colonne Profil -->
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Profil</th>

        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700 cursor-pointer" wire:click="sortBy('lastname')">Nom</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Prénom</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Date de naissance</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700 cursor-pointer" wire:click="sortBy('email')">Email</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Téléphone</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Adresse</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Ville Choisie</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Type de formation</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Niveau atteint</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Etre mis(e) en contact avec un salon partenaire</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Statut</th>
        <th class="px-6 py-4 text-left text-base font-semibold text-gray-700">Date</th>
        <th class="px-6 py-4 text-right text-base font-semibold text-gray-700">Actions</th>
      </tr>
    </thead>

    <tbody class="bg-white divide-y divide-gray-100">
      @forelse($candidates as $candidate)
        <tr class="hover:bg-gray-50">
          <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $candidate->id }}</td>

          <!-- Cellule Profil : avatar + nom complet (plus grande) -->
          <td class="px-6 py-4 text-sm">
            <div class="flex items-center">
              @php
               $fille = str_replace('profils/', '', $candidate->profile_photo_path);
              @endphp 
                
              <img
                src="{{ route('docs.display', ['filename' => $fille]) }}"
                alt="Avatar {{ $candidate->lastname }}"
                class="w-12 h-12 rounded-full object-cover shadow-sm mr-4"
              />
              <div class="min-w-0">
                <div class="text-sm font-semibold text-gray-800 truncate">
                  {{ $candidate->lastname }} {{ $candidate->firstname }}
                </div>
                <div class="text-xs text-gray-500 truncate">{{ $candidate->type_inscription ?? '' }}</div>
              </div>
            </div>
          </td>

          <td class="px-6 py-4 text-sm">{{ $candidate->lastname }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->firstname }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->birthdate->format('d/m/Y') }}</td>
          <td class="px-6 py-4 text-sm truncate max-w-[220px]">{{ $candidate->email }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->phone }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->address }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->city }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->type_formation }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->level }}</td>
          <td class="px-6 py-4 text-sm">{{ $candidate->contact_salon }}</td>
          <td class="px-6 py-4 text-sm">
            <span
              class="{{ ($candidate->is_valide==1)?'bg-warning text-pink-600':'En attente de paiement' }}  px-4 py-2 rounded-full font-medium flex items-center">
                  <i data-feather="home" class="mr-2 w-4 h-4"></i> Accueil
            </span>
            {{ ($candidate->is_valide==1)?"Paiement completé":'En attente de paiement' }}
          </td>
          <td class="px-6 py-4 text-sm">{{ $candidate->created_at->format('d/m/Y H:i') }}</td>

          <td class="px-6 py-4 text-right text-sm">
            <div class="flex justify-end items-center gap-3">
              @if($fille)
                <a href="{{route('docs.display', ['filename' => $fille]) }}" target="_blank" class="text-sm text-blue-600 underline">Photo</a>
              @endif

              <button
                onclick="confirm('Supprimer cet enregistrement ?') || event.stopImmediatePropagation()"
                wire:click="deleteCandidate({{ $candidate->id }})"
                class="text-sm text-rose-600 hover:underline"
              >
                Suppr
              </button>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="10" class="px-6 py-8 text-center text-sm text-gray-500">Aucun enregistrement trouvé.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
  @else
    <form wire:submit.prevent="getPassword" class="bg-white/95 rounded-2xl p-4 sm:p-6 md:p-8 shadow-2xl" novalidate>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Nom -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe <span class="text-red-600">*</span></label>
          <input id="password" wire:model.livewire="password" type="text" required
                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
                placeholder="Mot de passe" />
          @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>


      <!-- Buttons -->
      <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:gap-3 gap-3">
        <button
          type="submit"
          wire:loading.attr="disabled"
          class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-full bg-[var(--primary-pink)] text-white font-medium shadow hover:opacity-95 focus:outline-none focus:ring-4 focus:ring-pink-200"
        >
          <span>Valider</span>
          <svg wire:loading wire:target="getPassword" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
        </button>
      </div>
    </form>
  @endif
  
</div>
