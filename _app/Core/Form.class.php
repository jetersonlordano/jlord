<?php

class Form
{
    /**
     * Cria um input HTML padrão do sistema
     * @param String $type Tipo de input
     * @param String $name Nome e Id
     * @param String $label Label
     * @param String $title Título
     * @param String $width Largura automatica -> null, 'input-width-auto' ou 'input-width-50'
     * @param Type $value Valor do input
     * @param Boolean $required
     * @param Int $maxlength Máximo de caracteres
     * @param String $add Atributos adicionais
     * @param String $icon Icone na class do campo
     */
    public static function Input($type, $name, $label, $title, $width = null, $value = null, $required = true, $maxlength = null, $add = null, $icon = null)
    {
        $width = $width ? ' ' . $width : null;
        $iconType = $icon ? ' input-icon' : null;

        $html = "<div class=\"input-field{$iconType}{$width}\">";
        $html .= "<label class=\"label\" for=\"{$name}\">";
        $html .= $icon ? "<i class=\"icon {$icon}\"></i>" : null;
        $html .= "{$label}</label>";
        $html .= "<input type=\"{$type}\" id=\"{$name}\" ";
        $html .= 'name="' . ($type == 'file' ? $name . '[]' : $name) . '" ';
        $html .= " title=\"{$title}\" placeholder=\"{$title}\"";
        $html .= $required ? ' required' : null;
        $html .= $value ? ' value="' . $value . '"' : null;
        $html .= $maxlength ? ' maxlength="' . $maxlength . '" ' : null;
        $html .= $add . '><div class="input-feedback">Preencha este campo</div>';
        return $html . '</div>';
    }

    public static function Textarea($name, $label, $title, $width = null, $value = null, $required = true, $maxlength = null, $add = null)
    {
        $html = '<div class="input-field ' . $width . '"><label class="label" for="' . $name . '">' . $label . '</label>';
        $html .= '<textarea id="' . $name . '" name="' . $name . '" ';
        $html .= 'title="' . $title . '" placeholder="' . $title . '" ';
        $html .= $add;
        $html .= $required ? ' required' : null;
        $html .= $maxlength ? ' maxlength="' . $maxlength . '" ' : null;
        $html .= '>' . $value . '</textarea><div class="input-feedback">Preencha este campo</div></div>';
        return $html;
    }

    public static function Select($name, $label, $title, $width = null, array $value, $selected = null, $required = true, $add = null)
    {
        $html = '<div class="input-field ' . $width . '"><label class="label" for="' . $name . '">' . $label . '</label>';
        $html .= '<select id="' . $name . '" name="' . $name . '" title="' . $title . '"';
        $html .= $required ? ' required ' : null;
        $html .= $add . '>';
        foreach ($value as $key => $value) {
            $optSel = $key == $selected ? ' selected' : null;
            $html .= '<option value="' . $key . '" ' . $optSel . '>' . $value . '</option>';
        }
        $html .= '</select><div class="input-feedback">Selecione um valor</div></div>';
        return $html;
    }

    public static function Save($str, $loaderId, $formId, $block = false, $add = null)
    {
        $formId = $formId ? ' form="' . $formId . '"' : null;
        $html = '<button ' . $formId . ' type="submit" class="btn btn-main btn-form" ' . $add . '>';
        $html .= '<i class="fa fa-save"></i> ' . $str;
        $html .= '<span id="' . $loaderId . '" class="btn-form-loader"><i class="fa fa-spinner fa-pulse"></i></span></button>';

        if (!$block) {return $html;} else {return '<div class="block width-100 radius-inherit">' . $html . '</div>';}

    }
    public static function Interrupter($name, $title, $checked = 0, $formId, $add = null)
    {
        $checked = $checked ? ' checked' : null;
        $html = '<div class="interrupter">';
        $html .= '<input hidden type="checkbox" id="' . $name . '" form="' . $formId . '" ';
        $html .= 'name="' . $name . '"' . $checked . '>';
        $html .= '<label for="' . $name . '" title="' . $title . '">';
        $html .= '<span class="lever"></span></label></div>';
        return $html;
    }

    public static function checkbox($str, $name, $checked = 0, $inline = false, $width = null, $add = null)
    {
        $inline = $inline ? '-inline' : ' align-items-center';
        $checked = $checked ? ' checked' : null;
        $html = "<div class=\"input-field{$inline}{$width}\">";
        $html .= '<div class="checkbox">';
        $html .= "<input {$checked} id=\"{$name}\" type=\"checkbox\" name=\"{$name}\" {$add}>";
        $html .= "<label for=\"{$name}\" title=\"{$str}\"><span class=\"fa fa-check\"></span></label>";
        $html .= "</div><label for=\"{$name}\" class=\"label\" title=\"{$str}\">{$str}</label></div>";
        return $html;

    }

}
