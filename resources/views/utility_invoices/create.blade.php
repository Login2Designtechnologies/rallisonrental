<div id="monthNav" class="d-flex justify-content-center align-items-center mb-3">
  <span id="prevMonth" class="month-arrow fs-4 me-3" style="cursor:pointer">←</span>
  <span id="monthLabel" class="fw-bold text-dark"></span>
  <span id="nextMonth" class="month-arrow fs-4 ms-3" style="cursor:pointer">→</span>
</div>

<!-- Company Details -->
<div id="companyDetails" property_id="{{ $property->id }}">
  <input type="hidden" id="invoiceMonth" value="{{ $invoiceMonth ?? date('Y-m') }}">

  @php
    $utilitiescatg = DB::table('utilities_catg')->where('property_id',$property->id)->where('user_id',auth()->id())->where('status','1')->get();
  @endphp

  @forelse($utilitiescatg as $u)

    <!-- Start Date & End Date Inputs -->
   <!-- Start Date & End Date Inputs -->
<div class="card table-custom-card">
  <div class="card-body">
    <div class="d-flex gap-2 mb-3 justify-content-between">
      <!-- Company Name -->
      <h4 class="mt-4">{{ $u->name }}</h4>
      <div class="d-flex">
        <div class="me-2">
        <label for="startDate" class="form-label mb-1">Start Date</label>
        <input 
            class="form-control custom-datepicker datepicker-input" 
            placeholder="Start Date" 
            type="text" 
            style="max-width:150px;"
            value="{{ \Carbon\Carbon::parse($u->created_at)->format('m-d-Y') }}">
      </div>
          
      <div>
        <label for="endDate" class="form-label mb-1">End Date</label>
        <input 
            class="form-control custom-datepicker datepicker-input" 
            placeholder="End Date" 
            type="text" 
            style="max-width:150px;"
            value="{{ \Carbon\Carbon::parse($u->created_at)->format('m-d-Y') }}">
      </div>
      </div>
    </div>

        <!-- Categories Table -->
        <div class="table-responsive">
          <table class="table table-bordered custom-bg-table">
            <thead>
              <tr>
                <th>Category</th>
                <th>Price ($)</th>
                @if($property)
                   @php
                    $tenantsdata = DB::table('tenants')->where('property',$property->id)->where('parent_id',auth()->id())->first();
                    $tenantsuserdata = DB::table('users')->where('id',$tenantsdata->user_id)->get();
                  @endphp

                  @foreach($tenantsuserdata as $renter)
                    <th>{{ $renter->first_name }} {{ $renter->last_name }} (%)</th>
                  @endforeach
                @endif
                <th>Total (%)</th>
              </tr>
            </thead>

            <tbody>
              @forelse($utilitiescatg as $c)
                @if($c->status == 1)
                  <tr data-utility_id="{{ $u->id }}">
                    <td><span>{{ trim($c->sub_category_name) }}</span></td>

                    <!-- Price with $ -->
                    @php
                      $key = ($u->id ?: 'null').'|'.($c->name ?: '');
                      $existing = isset($amounts) && $amounts->has($key) ? number_format((float)$amounts->get($key)->total_amount, 2) : '0.00';
                    @endphp
                    <td contenteditable="true" class="price-cell"><span>${{ $existing }}</span></td>
                    @php
                        $tenantsdata = DB::table('tenants')->where('property',$property->id)->where('parent_id',auth()->id())->first();
                        $tenantsuserdata = DB::table('users')->where('id',$tenantsdata->user_id)->get();
                    @endphp
                    @if(!empty($property))
                      @foreach($tenantsuserdata as $renter)
                        <td contenteditable="true"
                            data-renter-id="{{ $renter->id }}"
                            class="renter-cell-{{ $renter->id }}"><span>0%</span></td>
                      @endforeach
                    @endif
                    <td class="cell-red total-row-cell"><span>0%</span></td>
                  </tr>
                @endif
              @empty
                <tr>
                  <td colspan="{{ 2 + ($property->tenants->count() ?: 0) }}" class="text-muted">No categories found.</td>
                </tr>
              @endforelse
              <!-- Totals row -->
              <tr class="fw-bold bg-light">
                <td>Total</td>
                <td class="column-total">$0</td>
                @if (!empty($property))
                  @foreach($tenantsuserdata as $renter)
                    <td
                        data-renter-is="{{ $renter->id }}"
                        class="renter-total-cell-{{ $renter->id }}">0%</td>
                  @endforeach
                @endif

                <td class="table-total-cell">0%</td>
              </tr>
            </tbody>
          </table>
        </div>


      <div class="row">
        <div class="col-md-6">
            <div id="upload-section">
              <input type="file" id="billUpload" hidden>
              <label for="billUpload" class="btn btn-info">Upload Bill</label>
            </div>

            <!-- After upload -->
            <div id="after-upload" class="mt-3 d-none">
              <button class="btn btn-success me-2">Upload Bill</button>
              <button class="btn btn-secondary" id="previousBtn">Preview </button>
            </div>
        
            <!-- Upload Bill Button -->
            <!-- <form id="uploadBillForm-{{ $u->id }}" enctype="multipart/form-data" class="d-flex gap-2">
                <input type="file" name="bill_file" id="billFileInput-{{ $u->id }}" class="form-control" style="max-width:150px;">
                <button type="button" class="btn btn-info" onclick="previewBill('{{ $u->id }}')">Upload / Preview Bill</button>
            </form> -->
        </div>
        <div class="col-md-6">
          <div class="text-end">
            <button class="btn btn-primary toggle-edit-btn"
                    data-utility-id="{{ $u->id }}">
                {{ (isset($amounts) && $utilitiescatg->filter(function($cat) use ($u, $amounts) { $k = ($u->id ?: 'null').'|'.($cat->name ?: ''); return $amounts->has($k) && (float)$amounts->get($k)->total_amount > 0; })->count()) ? 'EDIT / UPDATE' : 'SAVE' }}
            </button>
          </div>
        </div>
      </div>
  </div>
</div>

   
</div>

<!-- Bill Preview -->
<div id="billPreview-{{ $u->id }}" class="mt-2"></div>


  @empty
    <p class="text-muted">No utilities available for this property.</p>
  @endforelse
</div>

 
<!-- Grand Total Table -->
<div id="grandTotalTable" class="mt-4">
  <table class="table table-bordered table-striped mt-3 custom-bg-table">
    <thead>
      <tr>
        <th>Grand Total Price ($)</th>
        @if (!empty($property))
          @foreach($tenantsuserdata as $renter)
            <th>{{ $renter->first_name }} {{ $renter->last_name }} Total %</th>
          @endforeach
        @endif
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="grand-price-cell grand_total">$0.00</td>
        @if (!empty($property))
          @foreach($tenantsuserdata as $renter)
            <td class="grand-renter-total-{{ $renter->id }}">0%</td>
          @endforeach
        @endif
      </tr>
    </tbody>
  </table>
</div>

<div id="generateInvoiceBtnContainer" class="mt-3 d-flex justify-content-center gap-2" style="display:none;">
    <input type="date" name="due_date" value="{{ date('Y-m-d') }}" class="form-control" style="max-width:150px;">
    <button id="generateInvoiceBtn" class="btn btn-success">
        Generate Invoice
    </button>
</div>

<!-- JS for Bill Preview -->
<script>
function previewBill(utilityId) {
    const fileInput = document.getElementById(`billFileInput-${utilityId}`);
    const previewDiv = document.getElementById(`billPreview-${utilityId}`);
    previewDiv.innerHTML = '';

    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            if(file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100%';
                previewDiv.appendChild(img);
            } else if(file.type === 'application/pdf') {
                const iframe = document.createElement('iframe');
                iframe.src = e.target.result;
                iframe.style.width = '100%';
                iframe.style.height = '300px';
                previewDiv.appendChild(iframe);
            } else {
                const p = document.createElement('p');
                p.textContent = 'File preview not supported';
                previewDiv.appendChild(p);
            }
        }

        reader.readAsDataURL(file);
    }
}

</script>
<script>
document.querySelectorAll('.toggle-edit-btn').forEach(btn => {
    const utilityId = btn.dataset.utilityId;
    const table = document.querySelector(`tr[data-utility_id="${utilityId}"]`)?.closest('table');
    if (!table) return;

    // All editable cells in this table
    const editableCells = table.querySelectorAll('td[contenteditable="true"]');
    // All date inputs for this utility
    const dateInputs = table.closest('div').querySelectorAll('input[type="text"]');

    // Function to lock table
    const lockTable = () => {
        editableCells.forEach(cell => cell.setAttribute('contenteditable', 'false'));
        dateInputs.forEach(input => input.setAttribute('readonly', true));
    };

    // Function to unlock table
    const unlockTable = () => {
        editableCells.forEach(cell => cell.setAttribute('contenteditable', 'true'));
        dateInputs.forEach(input => input.removeAttribute('readonly'));
    };

    // On page load, lock table if already saved
    if (btn.textContent.trim() === 'EDIT / UPDATE') {
        lockTable();
    }

    btn.addEventListener('click', function() {
        if (this.textContent.trim() === 'SAVE') {
            // Save data logic here (AJAX or form submit)
            lockTable(); // Lock cells
            this.textContent = 'EDIT / UPDATE';
            alert('Saved successfully!');
        } else if (this.textContent.trim() === 'EDIT / UPDATE') {
            // Unlock table for editing
            unlockTable();
            this.textContent = 'SAVE';
        }
    });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const toggleButtons = document.querySelectorAll('.toggle-edit-btn');
    const generateInvoiceContainer = document.getElementById('generateInvoiceBtnContainer');
    const generateInvoiceBtn = document.getElementById('generateInvoiceBtn');

    // Check if Generate Invoice button should be visible
    const updateGrandTotal = () => {
        let grandPrice = 0;
        const renterTotals = {};
        document.querySelectorAll('table.custom-bg-table').forEach(table => {
            // per table: use column-total if available else sum rows
            const colTotal = table.querySelector('.column-total');
            if (colTotal) {
                grandPrice += parseFloat((colTotal.textContent || '').replace(/[^\d.\-]/g,'')) || 0;
            } else {
                table.querySelectorAll('tbody tr').forEach(row => {
                    const priceCell = row.querySelector('.price-cell');
                    if (priceCell) grandPrice += parseFloat((priceCell.textContent||'').replace(/[^\d.\-]/g,''))||0;
                });
            }
            // accumulate renter totals from footer cells
            table.querySelectorAll('td[class^="renter-total-cell-"]').forEach(cell => {
                const m = cell.className.match(/renter-total-cell-(\d+)/);
                if (!m) return;
                const rid = m[1];
                const val = parseFloat((cell.textContent||'').replace(/[^\d.\-]/g,''))||0;
                renterTotals[rid] = (renterTotals[rid]||0) + val;
            });
        });
        const grandCell = document.querySelector('.grand-price-cell.grand_total');
        if (grandCell) grandCell.textContent = '$' + grandPrice.toFixed(2);
        Object.keys(renterTotals).forEach(rid => {
            const cell = document.querySelector('.grand-renter-total-' + rid);
            if (cell) cell.textContent = renterTotals[rid].toFixed(2);
        });
    };

    const checkInvoiceReady = () => {
        let allSaved = true;
        let allBillsUploaded = true;
        let allDataFilled = true;

        toggleButtons.forEach(btn => {
            const utilityId = btn.dataset.utilityId;
            const table = document.querySelector(`tr[data-utility_id="${utilityId}"]`)?.closest('table');
            if (!table) return;

            // Table must be saved
            if (btn.textContent.trim() === 'SAVE') allSaved = false;

            // Check table data: require price > 0 only
            table.querySelectorAll('tbody tr').forEach(row => {
                const priceCell = row.querySelector('.price-cell');
                if (!priceCell) return;
                if ((parseFloat(priceCell.textContent.replace('$','')) || 0) <= 0) allDataFilled = false;
            });
        });

        // Check all file uploads
        document.querySelectorAll('input[type="file"]').forEach(fileInput => {
            if (!fileInput.dataset.uploaded || fileInput.dataset.uploaded !== "true") allBillsUploaded = false;
        });

        // Update grand total; show button only if all conditions met (saved + uploads + prices filled)
        updateGrandTotal();
        generateInvoiceContainer.style.display = (allSaved && allBillsUploaded && allDataFilled) ? 'flex' : 'none';
    };

    // Initialize Save/Edit buttons
    toggleButtons.forEach(btn => {
        const table = document.querySelector(`tr[data-utility_id="${btn.dataset.utilityId}"]`)?.closest('table');
        if(!table) return;

        // Lock saved tables
        if(btn.textContent.trim() === 'EDIT / UPDATE'){
            table.querySelectorAll('td[contenteditable="true"]').forEach(cell => cell.setAttribute('contenteditable','false'));
        }

        btn.addEventListener('click', function(){
            if(this.textContent.trim() === 'SAVE'){
                table.querySelectorAll('td[contenteditable="true"]').forEach(cell => cell.setAttribute('contenteditable','false'));
                this.textContent = 'EDIT / UPDATE';
                alert('Saved successfully!');
            } else {
                table.querySelectorAll('td[contenteditable="true"]').forEach(cell => cell.setAttribute('contenteditable','true'));
                this.textContent = 'SAVE';
            }
            checkInvoiceReady();
        });
    });

    // Handle file uploads
    document.querySelectorAll('input[type="file"]').forEach(fileInput => {
        fileInput.addEventListener('change', () => {
            if(fileInput.files && fileInput.files[0]){
                fileInput.dataset.uploaded = "true";
            }
            checkInvoiceReady();
        });
    });

    // Auto-update grand total on price edits
    document.addEventListener('input', (e) => {
        if (e.target && e.target.closest('.price-cell')) {
            updateGrandTotal();
        }
    });

    // Generate Invoice
    generateInvoiceBtn.addEventListener('click', () => {
        if(!confirm("Are you sure you want to generate the invoice? This action cannot be undone.")) return;

        const dueDate = document.querySelector('input[name="due_date"]').value;
        const monthMap = {January:'01',February:'02',March:'03',April:'04',May:'05',June:'06',July:'07',August:'08',September:'09',October:'10',November:'11',December:'12'};
        const label = (document.getElementById('monthLabel')?.textContent || '').trim();
        const parts = label.split(/\s+/);
        const invoiceMonth = (parts.length === 2 && monthMap[parts[0]]) ? `${parts[1]}-${monthMap[parts[0]]}` : new Date().toISOString().slice(0,7);

        // Build invoices payload from tables
        const invoicesMap = {};
        document.querySelectorAll('tr[data-utility_id]').forEach(row => {
            const utilityId = parseInt(row.getAttribute('data-utility_id')) || null;
            const table = row.closest('table');
            const price = parseFloat((row.querySelector('.price-cell')?.textContent || '').replace('$','')) || 0;
            const category = row.querySelector('td:first-child')?.textContent.trim() || '';
            const dateInputs = table.closest('div').querySelectorAll('input[type="text"]');
            const startDate = dateInputs[0]?.value || null;
            const endDate = dateInputs[1]?.value || null;

            row.querySelectorAll('td[class^="renter-cell-"]').forEach(cell => {
                const renterId = parseInt(cell.getAttribute('data-renter-id'));
                const pct = parseFloat((cell.textContent || '').replace('%','')) || 0;
                if (!renterId || pct <= 0) return;
                if(!invoicesMap[renterId]){
                    invoicesMap[renterId] = { tenant_id: renterId, amount: 0, details: [] };
                }
                const share = +(price * (pct/100)).toFixed(2);
                invoicesMap[renterId].amount += share;
                invoicesMap[renterId].details.push({
                    property_utility_id: utilityId,
                    category: category,
                    amount: share,
                    start_date: startDate,
                    end_date: endDate
                });
            });
        });

        const payload = {
            property_id: '{{ $property->id }}',
            invoice_month: invoiceMonth,
            due_date: dueDate,
            invoices: Object.values(invoicesMap)
        };

        fetch(`/utility-invoices/generate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(async (res) => {
            let data;
            try { data = await res.json(); } catch(e) { data = { ok:false, message: 'Invalid server response' }; }
            if (res.ok && data && data.ok){
                alert("Invoice has been generated and sent. No further changes allowed.");

                // Lock all tables and buttons
                toggleButtons.forEach(btn => {
                    const table = document.querySelector(`tr[data-utility_id="${btn.dataset.utilityId}"]`)?.closest('table');
                    if(!table) return;
                    table.querySelectorAll('td[contenteditable="true"]').forEach(cell => cell.setAttribute('contenteditable','false'));
                    btn.disabled = true;
                });

                // Disable file uploads
                document.querySelectorAll('input[type="file"]').forEach(fileInput => fileInput.disabled = true);

                // Disable Generate Invoice button
                generateInvoiceBtn.disabled = true;
            } else {
                const errors = (data && data.errors) ? JSON.stringify(data.errors) : '';
                console.error('Generate invoice error:', data);
                alert((data && data.message) ? data.message : 'Failed to create/update invoices. Check console.');
            }
        })
        .catch(err => {
            console.error('Network error generating invoice:', err);
            alert("Invoice generation error: " + err.message);
        });
    });

    // Initial check
    checkInvoiceReady();
});
</script>


<script>
    const billUpload = document.getElementById("billUpload");
    const uploadSection = document.getElementById("upload-section");
    const afterUpload = document.getElementById("after-upload");

    let uploadedFile = null;

    billUpload.addEventListener("change", function() {
      if (billUpload.files.length > 0) {
        uploadedFile = billUpload.files[0];
        uploadSection.classList.add("d-none");
        afterUpload.classList.remove("d-none");
      }
    });

    document.getElementById("previousBtn").addEventListener("click", function() {
      if (uploadedFile) {
        const fileURL = URL.createObjectURL(uploadedFile);
        // Open file in new tab
        window.open(fileURL, "_blank");
      }
    });
  </script>

