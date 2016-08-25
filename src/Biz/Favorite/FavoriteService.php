<?php

namespace Biz\Favorite;

interface FavoriteService
{
    public function getFavoritesCount($conditions);

    public function createFavorite($fields);

    public function deleteFavoriteByIdAndUserId($id, $userId);

    public function findFavoritesByUserId($userId);

    public function findFavoritesByKnowledgeId($knowledgeId);

}