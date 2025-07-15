<div class="my-4">
    @if($export)
        <div class="form-group d-flex justify-content-start align-items-center mx-2">
            <label class="d-flex justify-content-center align-items-center mx-2">Export:</label>
            <div class="center exportSelect">
                <select id="export_grid_view" class="custom-select sources" placeholder="export">
                    <option disabled selected>Select a type</option>
                    @foreach(config('gridview.default_export_formats', ['csv', 'xlsx', 'pdf']) as $format)
                        <option value="{{ $format }}">{{ strtoupper($format) }}</option>
                    @endforeach
                </select>
            </div>
            <label class="d-flex justify-content-center align-items-center mx-2">Export Columns:</label>
            @if(isset($exportColumns))
                <div>
                    @php $exportColumns = $exportColumns ?: []; @endphp
                    <select id="selected_columns_export" class="custom-select sources px-3" placeholder="export" multiple>
                        <option id="total_select">Select All</option>
                        @foreach($exportColumns as $label => $column)
                            <option value="{{ $column }}" selected>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    @endif

    <div class="auto-responsive">
        <table class="{{ config('gridview.styles.table_class') }}" cellpadding="0">
            <thead class="{{ config('gridview.styles.header_class') }}">
            <tr class="tblrow">{!! $headers !!}</tr>
            @if($filter) {!! $filter !!} @endif
            </thead>
            <tbody>{!! $rows !!}</tbody>
        </table>
    </div>
    <div class="mt-4">{!! $pagination !!}</div>
</div>

@if($filter)
    @push('js')
        <script src="{{ asset(config('gridview.assets.jquery_ui_js')) }}"></script>
        <script src="{{ asset(config('gridview.assets.jquery_multiselect_js')) }}"></script>
        <script>
            $("#refreshData").click(function() {
                let url = window.location.href;
                let baseUrl = url.split("?")[0];
                location.replace(baseUrl);
            });

            function sortTable(colName, sortState, attName, sort) {
                event.preventDefault();
                sort = sort === "ASC" ? "DESC" : "ASC";
                var form = $('<form>');
                form.append($('<input>', { type: 'hidden', name: colName, value: attName }));
                form.append($('<input>', { type: 'hidden', name: sortState + "[sortState]", value: sort }));
                $('.filter_field').each(function() {
                    form.append($('<input>', { type: 'hidden', name: $(this).attr('name'), value: $(this).val() }));
                });
                $('body').append(form);
                form.submit();
            }

            $('.filters input').on('keypress', function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    var form = $('<form>');
                    $('.filter_field').each(function() {
                        form.append($('<input>', { type: 'hidden', name: $(this).attr('name'), value: $(this).val() }));
                    });
                    $('body').append(form);
                    form.submit();
                }
            });

            @if($export)
            $('#export_grid_view').on('change', function() {
                var type = $(this).val();
                var queryParams = new URLSearchParams(window.location.search);
                queryParams.set('export', 'true');
                queryParams.set('type_export', type);
                let selectedColumns = getSelectValues($('#selected_columns_export')[0]);
                for (let column of selectedColumns) {
                    queryParams.append('selected_columns[' + column.text + ']', column.value);
                }
                var updatedUrl = window.location.pathname + '?' + queryParams.toString();
                $("#export_grid_view option").prop("selected", function() { return this.defaultSelected; });
                window.location.href = updatedUrl;
            });

            function getSelectValues(select) {
                var result = [];
                var options = select && select.options;
                for (var i = 0; i < options.length; i++) {
                    if (options[i].selected && options[i].id !== "total_select") {
                        result.push({ value: options[i].value, text: options[i].text });
                    }
                }
                return result;
            }

            $('#selected_columns_export').multiselect({ placeholder: 'Select Columns' });
            $(".ms-options-wrap>ul>li>label>input").prop("checked", true);
            $("#selected_columns_export option").attr("selected", "selected");
            $("#ms-opt-1").click(function() {
                if ($("#ms-opt-1").is(":checked")) {
                    $(".ms-options-wrap>ul>li>label>input").prop("checked", true);
                    $("#selected_columns_export option").attr("selected", "selected");
                } else {
                    $("#selected_columns_export option").attr("selected", null);
                    $(".ms-options-wrap>ul>li>label>input").prop("checked", false);
                }
            });
            @endif

            $(function() {
                $("table").resizable();
                $("table thead th").resizable({ handles: 'e' });
            });
        </script>
        @push('css')
            <link rel="stylesheet" href="{{ asset(config('gridview.assets.jquery_ui_css')) }}">
            <link rel="stylesheet" href="{{ asset(config('gridview.assets.jquery_multiselect_css')) }}">
            <style>
                .exportSelect { border: 1px solid black; border-radius: 5px; }
                .ms-options-wrap > button:focus, .ms-options-wrap > button { width: 142px !important; overflow: hidden; }
                table { display: table; box-sizing: border-box; text-indent: initial; border-spacing: 0 !important; border-collapse: collapse; }
                .header-gridView { background-color: #ccc !important; position: sticky; top: -20px; border-radius: 4px; z-index: 1; }
                .header-gridView .tblrow > th:hover { background-color: #d9d4ac; }
                .auto-responsive { height: 80vh !important; border-radius: 5px !important; overflow-y: auto !important; overflow-x: auto !important; margin-top: 2rem !important; position: relative; }
                thead tr th, tbody tr td { text-align: center !important; }
                tbody tr td { border-left: 1px solid #bdbdbd; }
                select { border: none !important; }
                .ms-options-wrap > button:focus, .ms-options-wrap > button { border-radius: 5px; border: 1px solid black; margin-top: -3px; min-height: 41px; width: 200px !important; }
                .ms-options-wrap ul { max-height: 229px !important; column-count: auto !important; list-style-type: none; padding-right: 0 !important; }
                .ms-options-wrap ul li { font-size: 11pt; border-bottom: 1px solid #d1d1d1; }
            </style>
        @endpush
    @endpush
@endif