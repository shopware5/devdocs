<?php declare(strict_types = 1);

namespace B2bAcl\Offer;

class AclConfig
{
    public static function getAclConfigArray(): array
    {
        return [
            'offer' =>
                [
                    'B2bOffer' =>
                        [
                            'index' => 'list',
                            'grid' => 'list',
                            'create' => 'create',
                            'update' => 'update',
                            'remove' => 'delete',
                            'new' => 'create',
                            'detail' => 'detail',
                            'edit' => 'detail',
                        ],
                ],
        ];
    }
}
