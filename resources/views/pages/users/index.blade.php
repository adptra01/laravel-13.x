<?php

use function Livewire\Volt\{layout, title};

layout('layouts.app');
title('Users');

?>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <livewire:users.index />
        </div>
    </div>
</x-app-layout>
