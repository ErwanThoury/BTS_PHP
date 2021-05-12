<?php
function my_empty(array $arr)
{
    foreach ($arr as $key => $value)
    {
        if ($value)
        {
            return false;
        }
    }

    return true;
}
?>   