<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public const PAGE_SIZE = 3;

    public const HEADER_COUNT = 'x-count';

    protected const SORT_DEFAULT = '1';

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'page' => 'integer',
            'sort' => 'string',
        ];
    }

    /**
     * @return int
     */
    public function getPageNumber(): int
    {
        return (int) $this->get('page', 1);
    }

    /**
     * @return string
     */
    public function getSortBy(): string
    {
        $sortByIdDirection = $this->get('sort', static::SORT_DEFAULT);

        return ($sortByIdDirection === static::SORT_DEFAULT ? '' : '-').'created_at';
    }
}
