<?php
$plans = App\Models\Plan::with('prices')->get();
foreach($plans as $p) {
    echo $p->name . ": ";
    foreach($p->prices as $pr) {
        echo $pr->billing_interval . '=' . $pr->amount . ' ';
    }
    echo "\n";
}
