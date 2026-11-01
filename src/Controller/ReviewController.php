<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Response;
use App\Service\ReviewService;
use App\View\Renderer;
use Throwable;

/** Handles the review page: asks the service for data and hands it to the view. */
final class ReviewController
{
    public function __construct(
        private readonly ReviewService $reviews,
        private readonly Renderer $renderer,
    ) {
    }

    public function index(): Response
    {
        try {
            return new Response($this->renderer->render('reviews', ['page' => $this->reviews->getPage()]));
        } catch (Throwable) {
            return new Response('Die Bewertungen konnten momentan nicht geladen werden.', 500);
        }
    }
}
