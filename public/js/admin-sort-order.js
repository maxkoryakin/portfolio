(function () {
  const params = new URLSearchParams(window.location.search);
  const crudAction = params.get('crudAction') || 'index';
  const crudController = params.get('crudControllerFqcn');

  if (crudAction !== 'index' || !crudController) {
    return;
  }

  const supportedCrudControllers = new Set([
    'App\\Controller\\Admin\\ProjectCrudController',
    'App\\Controller\\Admin\\SkillCrudController',
    'App\\Controller\\Admin\\WorkExperienceCrudController',
    'App\\Controller\\Admin\\EducationCrudController',
    'App\\Controller\\Admin\\HobbyCrudController',
  ]);

  if (!supportedCrudControllers.has(crudController)) {
    return;
  }

  const tableBody = document.querySelector('table.datagrid tbody');
  if (!(tableBody instanceof HTMLElement)) {
    return;
  }

  const sortableRows = Array.from(tableBody.querySelectorAll('tr[data-id]')).filter(
    (row) => row instanceof HTMLTableRowElement && !row.classList.contains('empty-row') && !row.classList.contains('no-results')
  );

  if (sortableRows.length < 2) {
    return;
  }

  const style = document.createElement('style');
  style.textContent = `
    .ea-sort-row { cursor: grab; }
    .ea-sort-row.ea-sort-row-dragging { opacity: 0.55; }
    .ea-sort-handle {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      margin-right: 8px;
      color: #9aa9c6;
      user-select: none;
      font-weight: 700;
      letter-spacing: 0.08em;
      cursor: grab;
    }
    .ea-sort-hint {
      margin-bottom: 10px;
      color: #7b879e;
      font-size: 13px;
    }
  `;
  document.head.appendChild(style);

  const hint = document.createElement('div');
  hint.className = 'ea-sort-hint';
  hint.textContent = 'Drag and drop rows to reorder items. Changes save automatically.';
  tableBody.parentElement?.insertAdjacentElement('beforebegin', hint);

  let draggedRow = null;

  const getRows = () => Array.from(tableBody.querySelectorAll('tr.ea-sort-row'));

  const getDragAfterElement = (y) => {
    const rows = getRows().filter((row) => row !== draggedRow);

    let closest = { offset: Number.NEGATIVE_INFINITY, element: null };
    for (const row of rows) {
      const box = row.getBoundingClientRect();
      const offset = y - box.top - box.height / 2;

      if (offset < 0 && offset > closest.offset) {
        closest = { offset, element: row };
      }
    }

    return closest.element;
  };

  const persistOrder = async () => {
    const ids = getRows()
      .map((row) => row.getAttribute('data-id'))
      .filter((id) => typeof id === 'string' && id !== '');

    try {
      await fetch('/admin/reorder-sort-order', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({
          crudController,
          ids,
        }),
      });
    } catch (_error) {
      // Keep UI usable even if save fails; server state can be retried by dragging again.
    }
  };

  for (const row of sortableRows) {
    const firstDataCell = row.querySelector('td');
    if (firstDataCell instanceof HTMLElement) {
      const handle = document.createElement('span');
      handle.className = 'ea-sort-handle';
      handle.textContent = '::';
      firstDataCell.prepend(handle);
    }

    row.classList.add('ea-sort-row');
    row.draggable = true;

    row.addEventListener('dragstart', (event) => {
      draggedRow = row;
      row.classList.add('ea-sort-row-dragging');
      if (event.dataTransfer) {
        event.dataTransfer.effectAllowed = 'move';
      }
    });

    row.addEventListener('dragend', () => {
      row.classList.remove('ea-sort-row-dragging');
      draggedRow = null;
      persistOrder();
    });
  }

  tableBody.addEventListener('dragover', (event) => {
    event.preventDefault();
    if (!(draggedRow instanceof HTMLTableRowElement)) {
      return;
    }

    const afterRow = getDragAfterElement(event.clientY);
    if (!afterRow) {
      tableBody.appendChild(draggedRow);
      return;
    }

    if (afterRow !== draggedRow) {
      tableBody.insertBefore(draggedRow, afterRow);
    }
  });
})();

