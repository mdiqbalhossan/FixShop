<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="title"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('account.store') }}" method="POST" id="form">
                    @csrf
                    <div id="method_sec">
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label fs-14 text-dark">{{ __('Account Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter name">
                        </div>
                        <div class="mb-3">
                            <label for="account_number" class="form-label fs-14 text-dark">{{ __('Account Number') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="account_number" name="account_number"
                                placeholder="Enter account number">
                        </div>
                        <div class="mb-3">
                            <label for="opening_balance" class="form-label fs-14 text-dark">{{ __('Opening Balance') }}
                                <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="opening_balance" name="opening_balance"
                                placeholder="Enter opening balance">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>