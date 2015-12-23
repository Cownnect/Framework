<?php

/*
 * Comments Plugin by Cownnect Developpers
 *
 * Copyright (c) 2015 Cownnect, Inc.

    MIT license

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in
    all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
    THE SOFTWARE.
 */

namespace CownnectFramework\Plugins;

class Comments {

    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;

        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
    }

    /**
     * get each comments by ID
     * @param $post_id
     * @return array
     */
    public function findAllById($post_id) {
        $req = $this->pdo->prepare('SELECT * FROM codescomments WHERE post_id = ?');
        $req->execute([$post_id]);
        $comments = $req->fetchAll();
        $comments_by_id = [];
        foreach ($comments as $comment) {
            $comments_by_id[$comment->id] = $comment;
        }
        return $comments_by_id;
    }

    /**
     * Get all comments with comments children
     * @param $post_id
     * @param bool $unset_children
     * @return array
     */
    public function findAllWithChildren($post_id, $unset_children = true) {

        $comments = $comments_by_id = $this->findAllById($post_id);
        foreach ($comments as $id => $comment) {
            if ($comment->parent_id != 0) {
                $comments_by_id[$comment->parent_id]->children[] = $comment;
                if ($unset_children) {
                    unset($comments[$id]);
                }
            }
        }
        return $comments;
    }

    /**
     * Delete comment by ID and level up the childrens
     * @param $id
     */
    public function delete($id) {
        $comment = $this->find($id);

        $this->pdo->prepare('DELETE FROM codescomments WHERE id = ?')->execute([$id]);

        $this->pdo->prepare('UPDATE  codescomments SET parent_id = ?, depth = depth - 1 WHERE parent_id = ?')->execute([$comment->parent_id, $comment->id]);
    }

    /**
     * Delete comment and childrens
     * @param $id
     * @return int
     */
    public function deleteWithChildren($id) {
        $comment = $this->find($id);
        $comments = $this->findAllWithChildren($comment['post_id'], false);
        $ids = $this->getChildrenIds($comments[$comment['id']]);
        $ids[] = $comment['id'];

        return $this->pdo->exec('DELETE FROM codescomments WHERE id IN (' . implode(',', $ids) . ')');
    }

    /**
     * Get all chidren ids of a comment
     * @param $comment
     * @return array
     */
    private function getChildrenIds($comment) {
        $ids = [];
        foreach ($comment['children'] as $child) {
            $ids[] = $child['id'];
            if (isset($child['children'])) {
                $ids = array_merge($ids, $this->getChildrenIds($child));
            }
        }
        return $ids;
    }

}
