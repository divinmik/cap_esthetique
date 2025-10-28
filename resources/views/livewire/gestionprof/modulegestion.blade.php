<div>
     <x-slot name="title_page">
        Gestion module
    </x-slot>
     <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                   <p>
                        <button class="btn btn-flat-primary" data-bs-toggle="modal" data-bs-target="#save_prof">Enrégistre/Mis à jours</button>
                    </p>
                </div>
                <div class="card-body">
                    

                    <div wire:ignore>
                        <table id="datatable-buttons" class="table table-hover table-bordered table-striped dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mois/Année</th>
                                    <th>Titre</th>
                                    <th>Description</th>
                                    <th>Total contenu</th>
                                    <th>Date création</th>
                                    <th>Détail</th>
                                </tr>
                            </thead>
                            <tbody>
                               
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div>

</div>
