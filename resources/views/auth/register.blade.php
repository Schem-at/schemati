<?php use function Livewire\Volt\{mount, state, computed}; ?>

<x-app-layout>
    <div class="flex justify-center items-center min-h-screen text-white bg-base-200">
        <div class="w-full max-w-md">
            <div class="bg-base-200 p-8 rounded-lg shadow-xl">
                <h2 class="font-bold text-2xl text-white mb-4">
                    Register
                </h2>
                <p class="text-white mb-6">
                    To register, connect to the auth server:
                </p>
                <div class="bg-base-300 rounded-lg p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <span class="text-white">IP:</span>
                            <span class="text-white font-semibold">auth.schemat.io</span>
                        </div>
                        <button class="text-primary" onclick="copyToClipboard('auth.schemat.io')">
                            <i class="far fa-copy"></i>
                        </button>
                    </div>
                </div>
                <p class="text-white mb-4">
                    Once connected, type the following command:
                </p>
                <div class="bg-base-300 rounded-lg p-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-white  font-mono">/password &lt;password&gt;</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
