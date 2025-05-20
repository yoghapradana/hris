@extends('layouts.app') {{-- SB Admin 2 layout --}}
@section('title', 'Create Daily Timesheet')
@section('content')

    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Daily Timesheet</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form id="timesheet-form" action="{{ route('timesheets.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    

                    <button type="button" id="add-entry-btn" class="btn btn-secondary mb-3">Add Entry</button>

                    <div class="form-group">
                        <label for="total-hours">Total Hours (All Entries)</label>
                        <input type="text" id="total-hours" class="form-control bg-light" name="total_time" readonly>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        function initTimepickers(scope = document) {
            scope.querySelectorAll('.timepicker').forEach(el => {
                flatpickr(el, {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: "H:i", // 24-hour format like 13:45
                    time_24hr: true
                });
            });
        }

        function calcHours(start, end) {
            if (!start || !end) return '0h 0m';

            const [startHour, startMinute] = start.split(':').map(Number);
            const [endHour, endMinute] = end.split(':').map(Number);

            let startTotal = startHour * 60 + startMinute;
            let endTotal = endHour * 60 + endMinute;

            // Handle overnight shift (e.g., 22:00 to 02:00)
            if (endTotal < startTotal) {
                endTotal += 24 * 60;
            }

            const diffMinutes = endTotal - startTotal;
            const hours = Math.floor(diffMinutes / 60);
            const minutes = diffMinutes % 60;

            return `${hours}h ${minutes}m`;
        }


        function updateEntryTotals() {
            let totalMinutes = 0;

            document.querySelectorAll('.entry').forEach(entry => {
                const start = entry.querySelector('.start-time')?.value;
                const end = entry.querySelector('.end-time')?.value;

                if (start && end) {
                    const [startHour, startMinute] = start.split(':').map(Number);
                    const [endHour, endMinute] = end.split(':').map(Number);

                    let startTotal = startHour * 60 + startMinute;
                    let endTotal = endHour * 60 + endMinute;
                    if (endTotal < startTotal) endTotal += 1440;

                    const diff = endTotal - startTotal;
                    totalMinutes += diff;

                    const entryHours = Math.floor(diff / 60);
                    const entryMinutes = diff % 60;
                    entry.querySelector('.total-hours-entry').value = `${entryHours}h ${entryMinutes}m`;
                } else {
                    entry.querySelector('.total-hours-entry').value = '';
                }
            });

            const totalH = Math.floor(totalMinutes / 60);
            const totalM = totalMinutes % 60;
            document.getElementById('total-hours').value = `${totalH}h ${totalM}m`;
        }

        function updateEntryCaptions() {
            document.querySelectorAll('.entry').forEach((entry, index) => {
                const title = entry.querySelector('.entry-title');
                title.textContent = `Entry ${index + 1}`;

            });
        }
        function updateAddButtonLabel() {
            const btn = document.getElementById('add-entry-btn');
            const entryCount = document.querySelectorAll('.entry').length;
            btn.textContent = entryCount === 0 ? 'Add Entry' : 'Add Another Entry';
        }

        function addListeners(entry) {
            entry.querySelectorAll('.timepicker').forEach(input => {
                input.addEventListener('change', () => {
                    updateEntryTotals();
                });
            });
            entry.querySelector('.remove-entry-btn').addEventListener('click', () => {
                entry.remove();
                updateEntryTotals();
                updateEntryCaptions();
                updateAddButtonLabel();
            });
        }

        document.getElementById('add-entry-btn').addEventListener('click', () => {
            const container = document.getElementById('entries-container');
            const newEntry = document.createElement('div');
            const index = container.querySelectorAll('.entry').length;

            newEntry.classList.add('entry', 'border', 'rounded', 'p-3', 'mb-3');
            newEntry.innerHTML = `
                <h5 class="entry-title mb-3">Entry ${index + 1}</h5>
                <div class="form-row">
                    <div class="col">
                        <label>Start Time</label>
                        <input type="text" name="entries[${index}][time_start]" class="form-control timepicker start-time" placeholder="Select Time" required>
                    </div>
                    <div class="col">
                        <label>End Time</label>
                        <input type="text" name="entries[${index}][time_end]" class="form-control timepicker end-time" placeholder="Select Time" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <label>Total Hours</label>
                        <input type="text" class="form-control total-hours-entry name="entries[${index}][total_time]"bg-light" readonly>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <label>Job Code</label>
                    <input type="text" name="entries[${index}][job_code]" class="form-control job-code" placeholder="e.g., J1234" required>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="entries[${index}][job_descriptions]" class="form-control description" rows="4" placeholder="Describe your work..." required></textarea>
                </div>
                <div class="form-group">
                    <label>Remark</label>
                    <textarea name="entries[${index}][remark]" class="form-control remark" rows="2" placeholder="Additional notes (optional)"></textarea>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger remove-entry-btn">Remove</button>
                </div>
            `;
            container.appendChild(newEntry);
            addListeners(newEntry);
            //updateEntryCaptions();
            updateAddButtonLabel();
            initTimepickers(newEntry);
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('add-entry-btn').click();
            document.querySelectorAll('.entry').forEach(addListeners);
            updateEntryTotals();
            updateEntryCaptions();
            updateAddButtonLabel();
            initTimepickers();
        });
    </script>
@endpush