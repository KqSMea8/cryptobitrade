<?php

return [
    'accepted'              => ':attribute يجب أن تكون مقبولة.',
    'active_url'            => ':attribute ليس URL صالح.',
    'after'                 => ':attribute يجب أن يكون موعد بعد :date.',
    'after_or_equal'        => ':attribute يجب أن يكون موعد بعد أو يساوي :date.',
    'alpha'                 => ':attribute قد تحتوي فقط على الحروف.',
    'alpha_dash'            => ':attribute قد تحتوي فقط على حروف وأرقام ، وشرطات.',
    'alpha_num'             => ':attribute قد تحتوي فقط على حروف وأرقام.',
    'array'                 => ':attribute يجب أن يكون صفيف.',
    'before'                => ':attribute يجب أن يكون التاريخ قبل :date.',
    'before_or_equal'       => ':attribute يجب أن يكون قبل أو يساوي :date.',
    'between'               => [
        'array'     => ':attribute يجب أن يكون بين :min و :max البنود.',
        'file'      => ':attribute بين :min و :max كيلو بايت.',
        'numeric'   => ':attribute بين :min و :max.',
        'string'    => ':attribute بين :min و :max حرفا.',
    ],
    'boolean'               => ':attribute يجب أن تكون صحيحة أو خاطئة.',
    'confirmed'             => ':attribute تأكيد لا تتطابق.',
    'country'               => 'المحدد :attribute غير موجود أو غير صحيح',
    'custom'                => [
        'attribute-name'    => [
            'rule-name' => 'مخصص-رسالة',
        ],
    ],
    'date'                  => ':attribute ليس تاريخ صالح.',
    'date_format'           => ':attribute لا يطابق الشكل :format.',
    'different'             => ':attribute و :other يجب أن تكون مختلفة.',
    'digits'                => ':attribute أن يكون :digits أرقام.',
    'digits_between'        => ':attribute بين :min و :max أرقام.',
    'dimensions'            => ':attribute غير صالح أبعاد الصورة.',
    'distinct'              => ':attribute حقل يحتوي على قيمة مكررة.',
    'email'                 => ':attribute يجب أن يكون عنوان بريد إلكتروني صالح.',
    'exists'                => 'المحدد :attribute غير صالح.',
    'file'                  => ':attribute يجب أن يكون الملف.',
    'filled'                => ':attribute يجب أن يكون لها قيمة.',
    'image'                 => ':attribute يجب أن تكون صورة.',
    'in'                    => 'المحدد :attribute غير صالح.',
    'in_array'              => ':attribute ميدان لا وجود له في :other.',
    'integer'               => ':attribute يجب أن يكون عدد صحيح.',
    'ip'                    => ':attribute يجب أن يكون عنوان IP صالح.',
    'ipv4'                  => ':attribute يجب أن تكون صالحة عنوان IPv4.',
    'ipv6'                  => ':attribute يجب أن تكون صالحة عنوان IPv6.',
    'json'                  => ':attribute يجب أن تكون صالحة سلسلة JSON.',
    'max'                   => [
        'array'     => ':attribute قد لا يكون أكثر من :max البنود.',
        'file'      => ':attribute قد لا تكون أكبر من :max كيلو بايت.',
        'numeric'   => ':attribute قد لا تكون أكبر من :max.',
        'string'    => ':attribute قد لا تكون أكبر من :max حرفا.',
    ],
    'mimes'                 => ':attribute يجب أن يكون الملف من نوع: :values.',
    'mimetypes'             => ':attribute يجب أن يكون الملف من نوع: :values.',
    'min'                   => [
        'array'     => ':attribute يجب أن يكون على الأقل :min البنود.',
        'file'      => ':attribute يجب أن يكون على الأقل :min كيلو بايت.',
        'numeric'   => ':attribute يجب أن يكون على الأقل :min.',
        'string'    => ':attribute يجب أن يكون على الأقل :min حرفا.',
    ],
    'not_in'                => 'المحدد :attribute غير صالح.',
    'numeric'               => ':attribute يجب أن يكون عددا.',
    'present'               => ':attribute الميدانية يجب أن تكون موجودة.',
    'regex'                 => ':attribute تنسيق غير صالح.',
    'required'              => ':attribute الحقل مطلوب.',
    'required_if'           => ':attribute الحقل مطلوب عند :other :value.',
    'required_unless'       => ':attribute الحقل مطلوب إلا إذا :other في :values.',
    'required_with'         => ':attribute الحقل مطلوب عند :values.',
    'required_with_all'     => ':attribute الحقل مطلوب عند :values.',
    'required_without'      => ':attribute الحقل مطلوب عند :values غير موجودة.',
    'required_without_all'  => ':attribute الحقل مطلوب عندما لا :values.',
    'same'                  => ':attribute و :other يجب أن تتطابق.',
    'size'                  => [
        'array'     => ':attribute يجب أن تحتوي على :size البنود.',
        'file'      => ':attribute أن يكون :size كيلو بايت.',
        'numeric'   => ':attribute أن يكون :size.',
        'string'    => ':attribute أن يكون :size حرفا.',
    ],
    'string'                => ':attribute يجب أن تكون سلسلة.',
    'timezone'              => ':attribute يجب أن تكون صالحة المنطقة.',
    'unique'                => ':attribute وقد اتخذت بالفعل.',
    'uploaded'              => ':attribute فشلت في تحميل.',
    'url'                   => ':attribute تنسيق غير صالح.',
];
