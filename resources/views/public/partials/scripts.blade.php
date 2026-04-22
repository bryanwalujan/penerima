<script>
$(document).ready(function () {

    // ── Filter baris tabel berdasarkan skema ─────────────────────────────────
    function filterSkema(identifier, type, skema, isDosenContext) {
        let tbody = isDosenContext
            ? $(`.dosen-data[data-type="${type}"][data-id="${identifier}"]`)
            : $(`#${identifier} .category-data[data-type="${type}"]`);

        const rows = tbody.find('tr');
        rows.each(function () {
            const rowSkema = $(this).data('skema') || 'none';
            $(this).toggle(skema === 'all' || rowSkema === skema);
        });

        // Tampilkan baris "kosong" jika tidak ada yang visible
        const visibleRows = tbody.find('tr:visible').length;
        tbody.find('tr[data-skema="none"]').toggle(visibleRows === 0);
    }

    // ── Urutkan baris tabel berdasarkan tahun ────────────────────────────────
    function sortTable(tabId, order, isDosenContext) {
        let tbody = isDosenContext
            ? $(`#${tabId} .dosen-data`)
            : $(`#${tabId} .category-data`);

        const rows = tbody.find('tr').not('[data-skema="none"]').get();
        rows.sort(function (a, b) {
            const yearA = parseInt($(a).data('year')) || 0;
            const yearB = parseInt($(b).data('year')) || 0;
            return order === 'asc' ? yearA - yearB : yearB - yearA;
        });
        tbody.empty().append(rows);

        // Re-apply filter skema setelah sort
        const type = tbody.data('type');
        if (type === 'penelitian' || type === 'pengabdian') {
            const identifier = isDosenContext ? tbody.data('id') : tabId;
            const $activeSkema = $(`#${tabId}`)
                .closest(isDosenContext ? '.dosen-card' : '.p-6')
                .find('.skema-filter .tab-link.tab-active, .skema-filter a.tab-active');
            const skema = $activeSkema.length ? $activeSkema.data('skema') : 'all';
            filterSkema(identifier, type, skema, isDosenContext);
        }
    }

    // ── Toggle kartu dosen (buka / tutup) ───────────────────────────────────
    $('.toggle-button').on('click', function () {
        const $this   = $(this);
        const $target = $(`#${$this.data('toggle-target')}`);
        const isOpen  = $this.hasClass('active');

        $target.toggleClass('active', !isOpen);
        $this.toggleClass('active', !isOpen)
             .html(isOpen
                 ? 'Lihat Penelitian <i class="fas fa-chevron-down"></i>'
                 : 'Tutup <i class="fas fa-chevron-up"></i>');
    });

    // ── Tab utama (Penelitian / Pengabdian / HAKI / Paten) ──────────────────
    $('.tab-link:not(.skema-filter .tab-link)').on('click', function (e) {
        e.preventDefault();
        const $this  = $(this);
        const tabId  = $this.data('tab');
        const dosenId = $this.closest('.dosen-card').data('dosen-id');
        const type   = tabId.split('-')[0];

        $this.closest('.tab-group').find('.tab-link').removeClass('tab-active');
        $this.addClass('tab-active');

        $this.closest('.dosen-card').find('.tab-content').addClass('hidden');
        $(`#${tabId}`).removeClass('hidden');

        if (type === 'penelitian' || type === 'pengabdian') {
            const skema = new URLSearchParams(window.location.search).get('skema') || 'all';
            const $skemaTab = $this.closest('.dosen-card').find(`.skema-filter .tab-link[data-skema="${skema}"]`);
            $this.closest('.dosen-card').find('.skema-filter .tab-link').removeClass('tab-active');
            ($skemaTab.length ? $skemaTab : $this.closest('.dosen-card').find('.skema-filter .tab-link[data-skema="all"]'))
                .addClass('tab-active');
            filterSkema(dosenId, type, skema, true);
        }

        sortTable(tabId, 'desc', true);
        $(`#${tabId}`).find('.sort-button[data-sort-order="desc"]').addClass('active');
    });

    // ── Filter skema untuk kartu dosen ──────────────────────────────────────
    $('.skema-filter .tab-link').on('click', function (e) {
        e.preventDefault();
        const $this  = $(this);
        const skema  = $this.data('skema');
        const tabId  = $this.data('tab');
        const dosenId = $this.closest('.dosen-card').data('dosen-id');
        const type   = tabId.split('-')[0];

        $this.closest('.skema-filter').find('.tab-link').removeClass('tab-active');
        $this.addClass('tab-active');

        const url = new URL(window.location);
        url.searchParams.set('skema', skema);
        window.history.pushState({}, '', url);

        filterSkema(dosenId, type, skema, true);
        sortTable(tabId, 'desc', true);
        $(`#${tabId}`).find('.sort-button[data-sort-order="desc"]').addClass('active');
    });

    // ── Filter skema untuk tampilan kategori ────────────────────────────────
    $('.skema-filter a').on('click', function (e) {
        e.preventDefault();
        const $this   = $(this);
        const skema   = $this.data('skema');
        const tableId = $this.closest('.p-6').find('.portfolio-table').attr('id');
        const type    = $this.closest('.p-6').find('.category-data').data('type');

        $this.closest('.skema-filter').find('a').removeClass('tab-active text-blue-600 text-green-600');
        $this.addClass('tab-active').addClass(type === 'penelitian' ? 'text-blue-600' : 'text-green-600');

        filterSkema(tableId, type, skema, false);
        sortTable(tableId, 'desc', false);
        $(`#${tableId}`).closest('.p-6').find('.sort-button[data-sort-order="desc"]').addClass('active');
    });

    // ── Tombol sort ──────────────────────────────────────────────────────────
    $('.sort-button').on('click', function () {
        const $this         = $(this);
        const tabId         = $this.data('sort-target');
        const order         = $this.data('sort-order');
        const isDosenContext = $(`#${tabId}`).hasClass('tab-content');

        const $parent = isDosenContext ? $(`#${tabId}`) : $(`#${tabId}`).closest('.p-6');
        $parent.find('.sort-button').removeClass('active asc');
        $this.addClass('active');
        if (order === 'asc') $this.addClass('asc');

        sortTable(tabId, order, isDosenContext);
    });

    // ── Inisialisasi kartu dosen ─────────────────────────────────────────────
    $('.dosen-card').each(function () {
        const dosenId = $(this).data('dosen-id');
        const skema   = new URLSearchParams(window.location.search).get('skema') || 'all';
        const $active = $(this).find('.tab-link.tab-active');

        if (!$active.length) return;

        const tabId = $active.data('tab');
        const type  = tabId.split('-')[0];

        $(this).find('.tab-content').addClass('hidden');
        $(`#${tabId}`).removeClass('hidden');

        if (type === 'penelitian' || type === 'pengabdian') {
            const $skemaTab = $(this).find(`.skema-filter .tab-link[data-skema="${skema}"]`);
            $(this).find('.skema-filter .tab-link').removeClass('tab-active');
            ($skemaTab.length
                ? $skemaTab
                : $(this).find('.skema-filter .tab-link[data-skema="all"]')
            ).addClass('tab-active');
            filterSkema(dosenId, type, skema, true);
        }

        sortTable(tabId, 'desc', true);
        $(`#${tabId}`).find('.sort-button[data-sort-order="desc"]').addClass('active');
    });

    // ── Inisialisasi tabel kategori ─────────────────────────────────────────
    $('.portfolio-table').each(function () {
        const tableId = $(this).attr('id');
        const type    = $(this).find('.category-data').data('type');
        const skema   = new URLSearchParams(window.location.search).get('skema') || 'all';

        if (type === 'penelitian' || type === 'pengabdian') {
            const color    = type === 'penelitian' ? 'text-blue-600' : 'text-green-600';
            const $skemaTab = $(this).closest('.p-6').find(`.skema-filter a[data-skema="${skema}"]`);
            $(this).closest('.p-6').find('.skema-filter a').removeClass('tab-active text-blue-600 text-green-600');
            ($skemaTab.length
                ? $skemaTab
                : $(this).closest('.p-6').find('.skema-filter a[data-skema="all"]')
            ).addClass(`tab-active ${color}`);
            filterSkema(tableId, type, skema, false);
        }

        sortTable(tableId, 'desc', false);
        $(this).closest('.p-6').find('.sort-button[data-sort-order="desc"]').addClass('active');
    });
});
</script>