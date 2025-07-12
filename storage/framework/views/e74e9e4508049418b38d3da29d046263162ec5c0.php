<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" <?php echo e(Metronic::printAttrs('html')); ?>

    <?php echo e(Metronic::printClasses('html')); ?>>

<head>
    <meta charset="utf-8" />

    
    <title><?php echo e(config('app.name')); ?> | <?php echo $__env->yieldContent('title', $page_title ?? ''); ?></title>

    
    <meta name="description" content="<?php echo $__env->yieldContent('page_description', $page_description ?? ''); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <link rel="shortcut icon"
        href=" />

    
    <?php echo e(Metronic::getGoogleFontsInclude()); ?>


    
    <?php $__currentLoopData = config('layout.resources.css'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $style): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<link href="<?php echo e(config('layout.self.rtl') ? asset(Metronic::rtlCssPath($style)) : asset($style)); ?>"
        rel="stylesheet" type="text/css" />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php $__currentLoopData = Metronic::initThemes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <link href="<?php echo e(config('layout.self.rtl') ? asset(Metronic::rtlCssPath($theme)) : asset($theme)); ?>"
            rel="stylesheet" type="text/css" />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <link href="<?php echo e(asset('css/custom.css')); ?>" rel="stylesheet" type="text/css" />
    
    <?php echo $__env->yieldContent('styles'); ?>
    <script>
        var APP_URL = `<?php echo e(url('/')); ?>`;
        var CSRF_Token = `<?php echo e(csrf_token()); ?>`;
    </script>

</head>

<body <?php echo e(Metronic::printAttrs('body')); ?> <?php echo e(Metronic::printClasses('body')); ?>>

    <?php if(config('layout.page-loader.type') != ''): ?>
        <?php echo $__env->make('admin.layout.partials._page-loader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php echo $__env->make('admin.layout.base._layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script>
        var HOST_URL = "";
    </script>
    
    <script>
        var KTAppSettings = `<?php echo json_encode(config('layout.js'), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES); ?>`;
    </script>

    
    <?php $__currentLoopData = config('layout.resources.js'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $script): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <script src="<?php echo e(asset($script)); ?>" type="text/javascript"></script>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    <script src="<?php echo e(asset('js/custom.js')); ?>" type="text/javascript"></script>
    
    <?php echo $__env->yieldContent('scripts'); ?>

    <script type="text/javascript">
        $(function() {
            //Datemask dd/mm/yyyy
            $("#datemask").inputmask("dd/mm/yyyy", {
                "placeholder": "dd/mm/yyyy"
            });
            //Datemask2 mm/dd/yyyy
            $("#datemask2").inputmask("mm/dd/yyyy", {
                "placeholder": "mm/dd/yyyy"
            });
            //Money Euro
            $("[data-mask]").inputmask();
            //Date range picker
            $('#reservation').daterangepicker();
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                format: 'MM/DD/YYYY h:mm A'
            });
            //Date range as a button
            $('#fromtodate').daterangepicker({
                    autoUpdateInput: false,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                        'Last 7 Days': [moment().subtract('days', 6), moment()],
                        'Last 30 Days': [moment().subtract('days', 29), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract(
                            'month', 1).endOf('month')]
                    },
                    // startDate: moment().subtract('days', 29),
                    endDate: moment()
                },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'));
                }
            );

            $('#fromtodate').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
            });
            //Timepicker
            $(".timepicker").timepicker({
                showInputs: false
            });
        });
    </script>
    <script>
        function makeAllTablesSortable(selector = 'table.sortable-table') {
            document.querySelectorAll(selector).forEach((table, tableIndex) => {
                const headers = table.querySelectorAll('th');
                let currentSortIndex = table.dataset.defaultSortIndex ? parseInt(table.dataset.defaultSortIndex) :
                0;
                let sortAsc = table.dataset.defaultSortOrder === 'desc' ? false : true;

                headers.forEach((header, columnIndex) => {
                    header.classList.add('sortable');
                    header.style.cursor = 'pointer';

                    header.addEventListener('click', () => {
                        if (currentSortIndex === columnIndex) {
                            sortAsc = !sortAsc;
                        } else {
                            sortAsc = true;
                            currentSortIndex = columnIndex;
                        }

                        sortTable(table, columnIndex, sortAsc);

                        headers.forEach(h => h.classList.remove('asc', 'desc', 'active'));
                        header.classList.add(sortAsc ? 'asc' : 'desc', 'active');
                    });
                });

                // Apply default sort once DOM is ready (even if in hidden tab)
                sortTable(table, currentSortIndex, sortAsc);
                headers[currentSortIndex]?.classList.add(sortAsc ? 'asc' : 'desc', 'active');
            });
        }

        function sortTable(table, columnIndex, ascending) {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            rows.sort((a, b) => {
                const cellA = a.children[columnIndex]?.innerText.trim().toLowerCase();
                const cellB = b.children[columnIndex]?.innerText.trim().toLowerCase();

                const isNumeric = !isNaN(cellA) && !isNaN(cellB);
                if (isNumeric) {
                    return (parseFloat(cellA) - parseFloat(cellB)) * (ascending ? 1 : -1);
                }

                return cellA.localeCompare(cellB) * (ascending ? 1 : -1);
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            makeAllTablesSortable();
        });
    </script>


</body>

</html>
<?php /**PATH /home/u791519547/domains/mrblogician.com/public_html/ktc/resources/views/admin/layout/default.blade.php ENDPATH**/ ?>