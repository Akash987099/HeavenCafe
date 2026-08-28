document.addEventListener('DOMContentLoaded', () => {
    const searchInputs = document.querySelectorAll('[data-search-input]');

    searchInputs.forEach((input) => {
        const tableKey = input.getAttribute('data-search-input');
        const table = document.querySelector(`[data-search-table="${tableKey}"]`);

        if (!table) {
            return;
        }

        const rows = Array.from(table.querySelectorAll('tbody tr[data-search-row]'));
        const emptyRow = table.querySelector('.admin-table-empty-row');
        const emptyCell = emptyRow ? emptyRow.querySelector('td') : null;
        const defaultEmptyText = emptyCell ? emptyCell.textContent : '';

        input.addEventListener('input', () => {
            const query = input.value.trim().toLowerCase();
            let visibleRows = 0;

            rows.forEach((row) => {
                const matches = query === '' || row.textContent.toLowerCase().includes(query);
                row.style.display = matches ? '' : 'none';

                if (matches) {
                    visibleRows += 1;
                }
            });

            if (emptyRow && emptyCell) {
                emptyRow.style.display = visibleRows === 0 ? '' : 'none';
                emptyCell.textContent = visibleRows === 0 && query !== ''
                    ? 'No matching records found.'
                    : defaultEmptyText;
            }
        });
    });
});
