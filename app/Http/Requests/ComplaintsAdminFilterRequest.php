<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Complaint;

class ComplaintsAdminFilterRequest extends Request
{
    /**
     * Атрибуты для перевода. См. resource/lang/ru/complaint.php
     * @var array
     */
    protected $translateAttributes = ['id', 'type', 'date.from', 'date.to'];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // TODO: установка разрешения
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|integer',
            'against_type' => 'nullable|string',
            'date.from' => 'nullable|date',
            'date.to' => 'nullable|date',
            'order' => 'nullable|array',
            'order.*' => 'nullable|string',
        ];
    }

    public function getTransTemplate()
    {
        return 'complaint.form.:attribute';
    }

}
