<?php
namespace App\Model;

interface CommentStatus {
    const pending   = 'pending';
    const granted = 'granted';
    const rejected = 'rejected';
}