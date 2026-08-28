<?php

return [
    'quote_request_confirmation' => [
        'subject' => 'Nous avons bien reçu votre demande sur mesure',
        'greeting' => 'Merci, :name',
        'received' => 'Nous avons bien reçu votre demande pour :type.',
        'reference' => 'Votre référence est **:reference**.',
        'configuration' => 'Votre configuration a été enregistrée avec **:modules :module**, :front et :material.',
        'module' => 'module',
        'modules' => 'modules',
        'price' => 'Le prix indicatif enregistré pour votre configuration est de **:price, TVA, livraison et pose comprises**.',
        'price_explanation' => 'Ce prix est calculé à partir des dimensions et des choix que vous avez indiqués. Nous confirmons le prix définitif après le contrôle technique et la prise de mesures, avant toute décision de votre part.',
        'next_steps' => 'Nous étudions personnellement votre espace, vos dimensions et vos préférences. Nous vous contactons ensuite pour discuter des possibilités et des prochaines étapes.',
        'closing' => 'Bien cordialement,',
    ],
    'quote_request_received' => [
        'subject' => 'Nouvelle demande sur mesure n° :number',
        'attachment_name' => 'Pièce jointe :number',
    ],
];
