<?php namespace Ysn\SuperCore\Types;

// comments, reviews ...
abstract class ModelStatus
{
    const DEFAULT = ModelStatus::PUBLISHED;

    const PUBLISHED = 0;
    const PENDING = 1; // INREVIEW
    const HIDDEN    = 2; // when reported by users for illegal content
}
