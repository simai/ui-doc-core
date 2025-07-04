<?php
namespace App\Helpers;

class Parsedown extends \ParsedownExtra
{
    protected function blockExample($Line)
    {
        if (preg_match('/^!example\s*$/', $Line['text'])) {
            return [
                'element' => [
                    'name' => 'div',
                    'attributes' => ['class' => 'example'],
                    'handler' => 'lines',
                    'text' => [],
                ],
            ];
        }
    }

    protected function blockExampleContinue($Line, array $Block): bool|array
    {
        if (preg_match('/^!endexample\s*$/', $Line['text'])) {
            return false;
        }
        $Block['element']['text'][] = $Line['body'];
        return $Block;
    }
}
?>
