<div>
 <form wire:submit.prevent="paiement_conf" class="bg-white/95 rounded-2xl p-4 sm:p-6 md:p-8 shadow-2xl" novalidate>
  <div class="grid grid-cols-1 sm:grid-cols-1 gap-4">
    <!-- Nom -->
    <div>
      <label for="lastname" class="block text-sm font-medium text-gray-700">Numéro d'inscription <span class="text-red-600">*</span> <div wire:loading> 
        <h6>
            ...
        </h6>
    </div></label>
      <input wire:model.live="code" type="text" required
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
             placeholder="ex: 001" />
        @if($candidat)
            <p class="text-green-600  font-bold mt-1">
                {{ $candidat->lastname }} 
                {{ $candidat->firstname }} 
            </p>
        @endif
        @if(empty($candidat) and $code)
            <p class="text-red-600  font-bold mt-1">
                Code inexistant ou déjà validé
            </p>
        @endif
      @error('code') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>
    <div>
      <label for="lastname" class="block text-sm font-medium text-gray-700">Numéro MTN Momo de paiement <span class="text-red-600">*</span></label>
      <input wire:model.live="payer_phone" type="text" required
             class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:ring-2 focus:ring-pink-300 px-3 py-2"
             placeholder="ex: +24206XXXXXXX" />
        
      @error('payer_phone') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>
  

  <!-- Buttons -->
  <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:gap-3 gap-3">
    <button
      type="submit"
      wire:loading.attr="disabled"
      class="inline-flex items-center justify-center gap-2 px-5 py-2 rounded-full bg-[var(--primary-pink)] text-white font-medium shadow hover:opacity-95 focus:outline-none focus:ring-4 focus:ring-pink-200"
    >
      <span>Confirmer</span>
      <svg wire:loading wire:target="paiement_conf" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
    </button>

    <button type="button" id="formReset" wire:click="resets" class="inline-flex items-center justify-center px-4 py-2 rounded-full border border-rose-200 bg-white text-rose-400 hover:bg-rose-50">
      Réinitialiser
    </button>
  </div>
      
</form>
  @if($polling)
      <div wire:poll.visible.1s="checkStatus">
      </div>
  @endif


</div>

@push('scripts')
<script>
  let swalInterval = null;

  window.addEventListener('swal', (e) => {
    const payload = e?.detail ?? {};
    const first = Array.isArray(payload) ? (payload[0] ?? {}) : payload;
    const action = first?.action;
    
    if (action === 'start') {
      // Si une ancienne popup existe, on nettoie d'abord
      try {
        if (typeof Swal.isVisible === 'function' && Swal.isVisible()) Swal.close();
      } catch (_) {}
      if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }

      Swal.fire({
        icon: first.icon || 'info',
        title: first.title || 'Vérification…',
        html: `
          <div>
            <div>Merci de confirmer votre paiement en tapant *105# .</div>
            <div>Temps restant : <b id="swal-remaining"></b> s</div>
          </div>
        `,
        timer: Number(first.timer) || 30000,
        timerProgressBar: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
          const html = Swal.getHtmlContainer();
          const $b = html ? html.querySelector('#swal-remaining') : null;

          // Par sécurité: clear avant de recréer
          if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }

          swalInterval = setInterval(() => {
            const left = typeof Swal.getTimerLeft === 'function' ? Swal.getTimerLeft() : null;
            if (left != null && $b) $b.textContent = Math.ceil(left / 1000);
          }, 100);
        },
        willClose: () => {
          if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }
        }
      });
      return;
    }

    if (action === 'status') {
      // 1) Stoppe TOUJOURS le timer et nettoie l’interval
      try {
        if (typeof Swal.isTimerRunning === 'function' && Swal.isTimerRunning()) {
          if (typeof Swal.stopTimer === 'function') Swal.stopTimer();
        }
      } catch (_) {}
      if (swalInterval) { clearInterval(swalInterval); swalInterval = null; }

      // 2) Si demandé, on ferme directement (utile pour "annulé")
      if (first.close === true) {
        try { if (typeof Swal.close === 'function') Swal.close(); } catch (_) {}
        return;
      }

      // 3) Sinon on met à jour la popup (sans timer)
      if (typeof Swal.isVisible === 'function' && Swal.isVisible()) {
        Swal.update({
          icon: first.icon || 'info',
          title: first.title || '',
          html: first.text ? `<p>${first.text}</p>` : '',
          showConfirmButton: true,
          timer: undefined,
          timerProgressBar: false,
          allowOutsideClick: true,
          allowEscapeKey: true,
        });
      } else {
        Swal.fire({
          icon: first.icon || 'info',
          title: first.title || '',
          text: first.text || '',
          showConfirmButton: true,
          allowOutsideClick: true,
          allowEscapeKey: true,
        });
      }
    }
  });
</script>
@endpush
