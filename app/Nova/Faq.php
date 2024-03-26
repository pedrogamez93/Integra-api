<?php

namespace App\Nova;

use Emilianotisato\NovaTinyMCE\NovaTinyMCE;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Faq extends Resource
{
    public static $group = 'Ayuda';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\Faq';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Título', 'title'),
            Text::make('Categoría', 'category'),

            NovaTinyMCE::make('Contenido', 'description')
                ->options([
                    'plugins' => [
                        'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                        'searchreplace wordcount visualblocks visualchars code fullscreen',
                        'insertdatetime media nonbreaking save table contextmenu directionality',
                        'emoticons template paste textcolor colorpicker textpattern',
                    ],
                    'toolbar' => 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media',
                    'relative_urls' => false,
                    'remove_script_host' => false,
                    'convert_urls' => true,
                    'use_lfm' => true,
                    'lfm_url' => 'filemanager',
                ]),

            Text::make('codigo del video', 'video'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * @return string
     */
    public static function label()
    {
        return 'Preguntas frecuentes';
    }

    /**
     * @return string
     */
    public static function singularLabel()
    {
        return 'Pregunta';
    }

}
