<div class="card-body pt-0">
    <!--begin::Custom fields-->
    <div class="d-flex flex-column mb-15 fv-row">
        <!--begin::Label-->
        <div class="fs-5 fw-bold form-label mb-3">
            Custom fields

            <span class="ms-2 cursor-pointer" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                data-bs-content="Add custom fields to the billing invoice." data-kt-initialized="1">
                <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                        class="path3"></span></i> </span>
        </div>
        <!--end::Label-->

        <!--begin::Table wrapper-->
        <div class="table-responsive">
            <!--begin::Table-->
            <div id="kt_create_new_custom_fields_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
                <div id="" class="table-responsive">
                    <table id="kt_create_new_custom_fields"
                        class="table align-middle table-row-dashed fw-semibold fs-6 gy-5 dataTable"
                        style="width: 100%;">
                        <colgroup>
                            <col data-dt-column="0" style="width: 237.766px;">
                            <col data-dt-column="1" style="width: 247.859px;">
                            <col data-dt-column="2" style="width: 64.375px;">
                        </colgroup>
                        <!--begin::Table head-->
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0" role="row">
                                <th class="pt-0 dt-orderable-none" data-dt-column="0" rowspan="1" colspan="1">
                                    <span class="dt-column-title">Field Name</span><span class="dt-column-order"></span>
                                </th>
                                <th class="pt-0 dt-orderable-none" data-dt-column="1" rowspan="1" colspan="1">
                                    <span class="dt-column-title">Field Value</span><span
                                        class="dt-column-order"></span></th>
                                <th class="pt-0 text-end dt-orderable-none" data-dt-column="2" rowspan="1"
                                    colspan="1"><span class="dt-column-title">Remove</span><span
                                        class="dt-column-order"></span></th>
                            </tr>
                        </thead>
                        <!--end::Table head-->

                        <!--begin::Table body-->
                        <tbody>
                            <tr>
                                <td>
                                    <input type="text" class="form-control form-control-solid" name="null-0"
                                        value="">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-solid" name="null-0"
                                        value="">
                                </td>
                                <td class="text-end">
                                    <button type="button"
                                        class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px me-3"
                                        data-kt-action="field_remove">
                                        <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span
                                                class="path2"></span><span class="path3"></span><span
                                                class="path4"></span><span class="path5"></span></i> </button>
                                </td>
                            </tr>
                        </tbody>
                        <!--end::Table body-->
                        <tfoot></tfoot>
                    </table>
                </div>
                <div id="" class="row">
                    <div id=""
                        class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                    </div>
                    <div id=""
                        class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    </div>
                </div>
            </div>
            <!--end:Table-->
        </div>
        <!--end::Table wrapper-->

        <!--begin::Add custom field-->
        <button type="button" class="btn btn-light-primary me-auto" id="kt_create_new_custom_fields_add">Add custom
            field</button>
        <!--end::Add custom field-->
    </div>
    <!--end::Custom fields-->

    <!--begin::Invoice footer-->
    <div class="d-flex flex-column mb-10 fv-row">
        <!--begin::Label-->
        <div class="fs-5 fw-bold form-label mb-3">
            Invoice footer

            <span class="ms-2 cursor-pointer" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true"
                data-bs-content="Add an addition invoice footer note." data-kt-initialized="1">
                <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                        class="path3"></span></i> </span>
        </div>
        <!--end::Label-->

        <textarea class="form-control form-control-solid rounded-3" rows="4"></textarea>
    </div>
    <!--end::Invoice footer-->

    <!--begin::Option-->
    <div class="d-flex flex-column mb-5 fv-row rounded-3 p-7 border border-dashed border-gray-300">
        <!--begin::Label-->
        <div class="fs-5 fw-bold form-label mb-3">
            Usage treshold

            <span class="ms-2 cursor-pointer" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true"
                data-bs-delay-hide="1000"
                data-bs-content="Thresholds help manage risk by limiting the unpaid usage balance a customer can accrue. Thresholds only measure and bill for metered usage (including discounts but excluding tax). <a href='#'>Learn more</a>."
                data-kt-initialized="1">
                <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                        class="path3"></span></i> </span>
        </div>
        <!--end::Label-->

        <!--begin::Checkbox-->
        <label class="form-check form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" checked="" value="1">
            <span class="form-check-label text-gray-600">
                Bill immediately if usage treshold reaches 80%.
            </span>
        </label>
        <!--end::Checkbox-->
    </div>
    <!--end::Option-->

    <!--begin::Option-->
    <div class="d-flex flex-column fv-row rounded-3 p-7 border border-dashed border-gray-300">
        <!--begin::Label-->
        <div class="fs-5 fw-bold form-label mb-3">
            Pro-rate billing

            <span class="ms-2 cursor-pointer" data-bs-toggle="popover" data-bs-trigger="hover focus"
                data-bs-html="true" data-bs-delay-hide="1000"
                data-bs-content="Pro-rated billing dynamically calculates the remainder amount leftover per billing cycle that is owed. <a href='#'>Learn more</a>."
                data-kt-initialized="1">
                <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                        class="path2"></span><span class="path3"></span></i> </span>
        </div>
        <!--end::Label-->

        <!--begin::Checkbox-->
        <label class="form-check form-check-custom form-check-solid">
            <input class="form-check-input" type="checkbox" value="1">
            <span class="form-check-label text-gray-600">
                Allow pro-rated billing when treshold usage is paid before end of billing cycle.
            </span>
        </label>
        <!--end::Checkbox-->
    </div>
    <!--end::Option-->
</div>
