<?php


if (! function_exists('avatar')) {
    function avatar($user = null) {
        if(!$user)
            return 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=6c7a91&color=ffffff'; // Exemplo: adicione '&size=200' para definir o tamanho do avatar como 200x200 pixels
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6c7a91&color=ffffff'; // Exemplo: adicione '&size=200' para definir o tamanho do avatar como 200x200 pixels
    }
}