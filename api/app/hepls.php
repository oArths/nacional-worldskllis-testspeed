<?php
function res($data, $status = 200){
    return response()->json($data, $status);

}