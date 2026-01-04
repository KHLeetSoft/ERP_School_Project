<div class="card-body">
    <form id="filtersForm" method="GET" action="{{ route('admin.communications.sms.index') }}">
        <div class="row">
            <!-- Search -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="search">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Search messages...">
                </div>
            </div>

            <!-- Status -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Priority -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select class="form-control" id="priority" name="priority">
                        <option value="">All Priorities</option>
                        @foreach($priorities as $priority)
                            <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                {{ ucfirst($priority) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Category -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Recipient Type -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="recipient_type">Recipient Type</label>
                    <select class="form-control" id="recipient_type" name="recipient_type">
                        <option value="">All Types</option>
                        @foreach($recipientTypes as $type)
                            <option value="{{ $type }}" {{ request('recipient_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <!-- Date From -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="date_from">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="{{ request('date_from') }}">
                </div>
            </div>

            <!-- Date To -->
            <div class="col-md-3">
                <div class="form-group">
                    <label for="date_to">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="{{ request('date_to') }}">
                </div>
            </div>

            <!-- Sort By -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="sort_by">Sort By</label>
                    <select class="form-control" id="sort_by" name="sort_by">
                        <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Created Date</option>
                        <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Status</option>
                        <option value="priority" {{ request('sort_by') == 'priority' ? 'selected' : '' }}>Priority</option>
                        <option value="category" {{ request('sort_by') == 'category' ? 'selected' : '' }}>Category</option>
                        <option value="message" {{ request('sort_by') == 'message' ? 'selected' : '' }}>Message</option>
                    </select>
                </div>
            </div>

            <!-- Sort Order -->
            <div class="col-md-2">
                <div class="form-group">
                    <label for="sort_order">Order</label>
                    <select class="form-control" id="sort_order" name="sort_order">
                        <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="col-md-2">
                <div class="form-group">
                    <label>&nbsp;</label>
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary btn-sm mr-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('admin.communications.sms.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
