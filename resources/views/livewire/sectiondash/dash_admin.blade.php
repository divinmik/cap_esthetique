<div class="row">
    <div class="col-xxl-9">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-user-tie"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total prof.</p>
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-user-graduate"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total candidat</p>
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-lg p-3">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 align-self-center">
                                        <div class="avatar-sm rounded bg-info-subtle text-info d-flex align-items-center justify-content-center">
                                            <span class="avatar-title">
                                                <i class="fas fa-file-archive"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="text-muted fw-medium mb-2">Total module</p>
                                        <h4 class="mb-0">0</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        @livewire('sectiondash.liste')
    </div>

    <div class="col-xxl-3">
        <div class="row">

            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <div class="card-icon text-muted"><i class="fas fa-sort-amount-up fs-14"></i></div>
                        <h4 class="card-title">Historique de facture</h4>
                        <div class="card-addon dropdown">
                            <button class="btn btn-label-primary py-0 btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Option <i class="mdi mdi-chevron-down fs-17 align-middle ms-1"></i></button>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated" style="">
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-icon"><i class="fa fa-poll"></i></div>
                                    <span class="dropdown-content">Report</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-icon"><i class="fa fa-chart-pie"></i></div>
                                    <span class="dropdown-content">Charts</span>
                                </a>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-icon"><i class="fa fa-chart-line"></i></div>
                                    <span class="dropdown-content">Statistics</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <div class="dropdown-icon"><i class="fa fa-cog"></i></div>
                                    <span class="dropdown-content">Settings</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="border-bottom text-center pb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <span class="text-primary fs-24 me-2"><i class="fas fa-arrow-circle-right"></i></span>
                                <h4 class="display-5 mb-0">0</h4>
                            </div>
                            <p class="text-muted mb-0">En attende de paiement</p>
                        </div>
                        <div class="d-flex justify-content-between py-3">
                            <p class="text-muted fs-5 mb-0">Details</p>

                        </div>
                        <div class="hstack gap-4 justify-content-center pb-3">
                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="text-info fs-22 me-2"><i class="fas fa-arrow-circle-up"></i></span>
                                    <h4 class="display-6 mb-0">0</h4>
                                </div>
                                <p class="text-muted mb-0">Facture encaisé</p>
                            </div>

                            <div class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <span class="text-danger fs-22 me-2"><i class="fas fa-arrow-circle-down"></i></span>
                                    <h4 class="display-6 mb-0">0</h4>
                                </div>
                                <p class="text-muted mb-0">Facture non encaisse</p>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <h5 class="fs-6 mb-0"><i class="fas fa-arrow-circle-up text-info me-2"></i>Mois/anne</h5>
                                <p class="text-muted mb-0">Total encaisse</p>
                                <p class="text-muted mb-0">total en attent</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
