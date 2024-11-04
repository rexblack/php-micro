<?php

namespace benignware\micro\middleware;

class Pagination
{
    protected $itemsPerPage;

    public function __construct($options = [])
    {
        $this->itemsPerPage = $options['itemsPerPage'] ?? 10; // Default to 10 items per page
    }

    public function augmentRequest($req)
    {
        // Setup pagination parameters if needed
        $req->params['page'] = $req->params['page'] ?? 1; // Default to page 1
        $req->params['itemsPerPage'] = $this->itemsPerPage;
    }

    public function augmentResponse($req, $res)
    {
        // Add the paginate method to the view using the 'use' method
        $res->view->use('paginate', function ($totalItems, $currentPage, $options = []) use ($req, $res) {
            $totalPages = ceil($totalItems / $this->itemsPerPage);

            // Use options or set defaults
            $paginationClass = $options['paginationClass'] ?? 'pagination';
            $pageItemClass = $options['pageItemClass'] ?? 'page-item';
            $pageLinkClass = $options['pageLinkClass'] ?? 'page-link';

            $html = '<nav aria-label="Page navigation">';
            $html .= '<ul class="' . htmlspecialchars($paginationClass) . '">';

            // Previous button
            $html .= '<li class="' . htmlspecialchars($pageItemClass) . ($currentPage == 1 ? ' disabled' : '') . '">';
            $html .= '<a class="' . htmlspecialchars($pageLinkClass) . '" href="' . ($currentPage > 1 ? $res->view->url('posts', ['page' => $currentPage - 1]) : '#') . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
            $html .= '</li>';

            // Page numbers
            for ($i = 1; $i <= $totalPages; $i++) {
              $activeClass = $currentPage == $i ? ' active' : '';
              $html .= '<li class="' . htmlspecialchars($pageItemClass) . $activeClass . '">';
              if ($i === 1) {
                  $html .= '<a class="' . htmlspecialchars($pageLinkClass) . '" href="' . $res->view->url('posts') . '">' . $i . '</a>'; // No params for the first page
              } else {
                  $html .= '<a class="' . htmlspecialchars($pageLinkClass) . '" href="' . $res->view->url('posts', ['page' => $i]) . '">' . $i . '</a>';
              }
              $html .= '</li>';
            }


            // Next button
            $html .= '<li class="' . htmlspecialchars($pageItemClass) . ($currentPage == $totalPages ? ' disabled' : '') . '">';
            $html .= '<a class="' . htmlspecialchars($pageLinkClass) . '" href="' . ($currentPage < $totalPages ? $res->view->url('posts', ['page' => $currentPage + 1]) : '#') . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>';
            $html .= '</li>';

            $html .= '</ul>';
            $html .= '</nav>';

            return $html; // Return HTML instead of echoing it
        });
    }

    public static function middleware($options = [])
    {
        // Return middleware function
        return function ($req, $res, $next) use ($options) {
            $middleware = new self($options);
            $middleware->augmentRequest($req);
            $middleware->augmentResponse($req, $res);
            $next();
        };
    }
}
