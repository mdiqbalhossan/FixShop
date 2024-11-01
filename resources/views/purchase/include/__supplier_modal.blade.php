<div class="modal fade" id="addSupplierModal" tabindex="-1"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">{{ __('Add Supplier') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label fs-14 text-dark">{{ __('Name') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Supplier Name">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fs-14 text-dark">{{ __('Email') }} <span
                            class="text-danger">*</span></label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Supplier Email">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label fs-14 text-dark">{{ __('Phone') }} <span
                            class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Supplier Phone">
                </div>
                <div class="mb-3">
                    <label for="company" class="form-label fs-14 text-dark">{{ __('Company') }} </label>
                    <input type="text" class="form-control" id="company" name="company" placeholder="Enter Supplier Company">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label fs-14 text-dark">{{ __('Address') }} </label>
                    <textarea name="address" id="address" cols="30" rows="2" class="form-control" placeholder="Enter Supplier Address"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-primary" id="add_supplier">{{ __('Add Supplier') }}</button>
            </div>
        </div>
    </div>
</div>
