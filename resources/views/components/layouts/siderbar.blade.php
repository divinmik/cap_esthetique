<div class="sidebar-left">

        <div data-simplebar class="h-100">

            <!--- Sidebar-menu -->
            <div id="sidebar-menu">
                <!-- Left Menu Start -->
                <ul class="left-menu list-unstyled" id="side-menu">
                    <li>
                        <a href="/dashboard" class="">
                            <i class="fas fa-desktop"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

             @if(auth()->user()->statut == "admin" or auth()->user()->statut == "agent")
            <li class="menu-title">Espace admin</li>
            
            <li class="menu-item">
                <a class='menu-link' href="{{ route('createprof') }}" wire:navigate>
                    <span class="menu-icon"><i class="fas fa-user-tie"></i></span>
                    <span class="menu-text"> Gestion prof  </span>
                </a>
            </li>
            <li class="menu-item">
                <a class='menu-link' href="{{ route('cand_ges') }}" wire:navigate>
                    <span class="menu-icon"><i class="fas fa-user-graduate"></i></span>
                    <span class="menu-text"> Gestion candidat  </span>
                </a>
            </li>
            
            <li class="menu-item">
                <a class='menu-link' href="{{ route('module_info') }}" wire:navigate>
                    <span class="menu-icon"><i class="fas fa-file-archive"></i></span>
                    <span class="menu-text"> Gestion module </span>
                </a>
            </li>

            <li class="menu-item">
                <a class='menu-link' href="{{ route('facture') }}" wire:navigate>
                    <span class="menu-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                    <span class="menu-text"> Gestion facture </span>
                </a>
            </li>
            <li class="menu-item">
                <a class='menu-link' href="{{ route('user_create') }}" wire:navigate>
                    <span class="menu-icon"><i class=" fas fa-user-friends"></i></span>
                    <span class="menu-text"> Gestion utilisateur </span>
                </a>
            </li>
            @endif
            @if(auth()->user()->statut == "professeur")
            <li class="menu-title">Espace prof</li>

           
            <li class="menu-item">
                <a class='menu-link' href="{{ route('pr.module_create') }}" wire:navigate>
                    <span class="menu-icon"><i class="fas fa-folder-plus"></i></span>
                    <span class="menu-text"> Module enrégistre </span>
                </a>
            </li>
            @endif
            @if(auth()->user()->statut == "candidat")
                <li class="menu-title">Espace candidat</li>

                <li class="menu-item">
                    <a class='menu-link' href="" wire:navigate>
                        <span class="menu-icon"><i class="fas fa-folder-open"></i></span>
                        <span class="menu-text">Module disponible</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a class='menu-link' href="">
                        <span class="menu-icon"><i class="fas fa-file-invoice-dollar"></i></span>
                        <span class="menu-text">Facture</span>
                    </a>
                </li>
            @endif
                </ul>
            </div>
            <!-- Sidebar -->
        </div>
    </div>