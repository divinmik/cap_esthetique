<x-guest-layout>
    <x-slot name="bg_img">
      admin/assets/images/bg_3.gif
    </x-slot>
   <div class="col-10 col-md-6 col-lg-4 col-xxl-3">
    <div class="card mb-0">
        <div class="card-body">
            <div class="text-center">
                <a href="/" class="logo-dark">
                    <img src="/admin/assets/images/logo-dark.png" alt="" height="50" class="auth-logo logo-dark mx-auto">
                </a>
                <a href="index.html" class="logo-dark">
                    <img src="/admin/assets/images/logo-light.png" alt="" height="50" class="auth-logo logo-light mx-auto">
                </a>
                

                <h4 class="mt-4">CAP COSMETIC</h4>
                <p class="text-muted">Page de connexion</p>
            </div>

            <div class="p-2 mt-5">
                <form method="POST" action="{{ route('login') }}">
                   
                   @csrf 
                   <div class="input-group auth-form-group-custom mb-3">
                        <span class="input-group-text bg-primary bg-opacity-10 fs-16 " id="basic-addon1"><i class="mdi mdi-account-outline auti-custom-input-icon"></i></span>
                        <input type="text" name="email_phone" class="form-control" placeholder="Entre numéro téléphone ou username" aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <div class="input-group auth-form-group-custom mb-3">
                        <span class="input-group-text bg-primary bg-opacity-10 fs-16" id="basic-addon2"><i class="mdi mdi-lock-outline auti-custom-input-icon"></i></span>
                        <input type="password" name="password" class="form-control" id="userpassword" placeholder="Enter password" aria-label="Username" aria-describedby="basic-addon1">
                    </div>

                    <div class="mb-sm-5">
                        <div class="form-check float-sm-start">
                            <input type="checkbox" class="form-check-input" id="customControlInline">
                            <label class="form-check-label" for="customControlInline">Se reappeller de moi</label>
                        </div>
                        <div class="float-sm-end">
                            <a href="auth-recoverpw.html" class="text-muted"><i class="mdi mdi-lock me-1"></i> Mot de passe oublié</a>
                        </div>
                    </div>

                    <div class="pt-3 text-center">
                        <button class="btn btn-primary w-xl waves-effect waves-light" type="submit">Se connecter</button>
                    </div>
                </form>
            </div>

            
        </div>
    </div>
</div>
</x-guest-layout>
