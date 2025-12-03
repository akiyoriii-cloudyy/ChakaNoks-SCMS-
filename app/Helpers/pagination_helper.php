<?php

if (!function_exists('render_pagination')) {
    /**
     * Render pagination controls
     *
     * @param object $pager CodeIgniter Pager object
     * @param string $pageParam Query parameter name for the page number
     * @return string HTML for pagination
     */
    function render_pagination($pager, $pageParam = 'page')
    {
        if (!$pager) {
            return '';
        }
        
        // Handle custom pager object (stdClass) or CodeIgniter Pager
        if (is_object($pager) && !method_exists($pager, 'getPageCount')) {
            // Custom pager object
            $currentPage = $pager->currentPage ?? 1;
            $totalPages = $pager->pageCount ?? 1;
            $perPage = $pager->perPage ?? 10;
            $total = $pager->total ?? 0;
        } else {
            // CodeIgniter Pager object
            $currentPage = $pager->getCurrentPage();
            $totalPages = $pager->getPageCount();
            $perPage = $pager->getPerPage();
            $total = $pager->getTotal();
        }
        
        if ($totalPages <= 1) {
            return '';
        }
        
        // Calculate range of displayed records
        $from = (($currentPage - 1) * $perPage) + 1;
        $to = min($currentPage * $perPage, $total);
        
        // Build query string preserving other parameters
        $request = service('request');
        $queryParams = $request->getGet();
        
        $html = '<div class="pagination-wrapper" style="display: flex; justify-content: space-between; align-items: center; padding: 20px; border-top: 1px solid #e2e8f0; background: #f9fafb;">';
        
        // Showing X to Y of Z results
        $html .= '<div class="pagination-info" style="color: #64748b; font-size: 0.875rem;">';
        $html .= "Showing <strong>{$from}</strong> to <strong>{$to}</strong> of <strong>{$total}</strong> results";
        $html .= '</div>';
        
        // Pagination controls
        $html .= '<nav class="pagination-controls" style="display: flex; gap: 4px;">';
        
        // Previous button
        if ($currentPage > 1) {
            $prevPage = $currentPage - 1;
            $queryParams[$pageParam] = $prevPage;
            $prevUrl = '?' . http_build_query($queryParams);
            $html .= '<a href="' . $prevUrl . '" class="pagination-btn" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: white; color: #334155; text-decoration: none; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'white\'">← Previous</a>';
        } else {
            $html .= '<span class="pagination-btn disabled" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f9fafb; color: #94a3b8; cursor: not-allowed;">← Previous</span>';
        }
        
        // Page numbers
        $html .= '<div style="display: flex; gap: 2px;">';
        
        // Calculate page range to show
        $range = 2; // Show 2 pages on each side of current page
        $start = max(1, $currentPage - $range);
        $end = min($totalPages, $currentPage + $range);
        
        // First page
        if ($start > 1) {
            $queryParams[$pageParam] = 1;
            $url = '?' . http_build_query($queryParams);
            $html .= '<a href="' . $url . '" class="pagination-number" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: white; color: #334155; text-decoration: none; min-width: 40px; text-align: center; transition: all 0.2s;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'white\'">1</a>';
            
            if ($start > 2) {
                $html .= '<span style="padding: 8px 4px; color: #94a3b8;">...</span>';
            }
        }
        
        // Page numbers in range
        for ($i = $start; $i <= $end; $i++) {
            if ($i == $currentPage) {
                $html .= '<span class="pagination-number active" style="padding: 8px 12px; border: 1px solid #2d5016; border-radius: 6px; background: linear-gradient(135deg, #2d5016 0%, #4a7c2a 100%); color: white; min-width: 40px; text-align: center; font-weight: 600;">' . $i . '</span>';
            } else {
                $queryParams[$pageParam] = $i;
                $url = '?' . http_build_query($queryParams);
                $html .= '<a href="' . $url . '" class="pagination-number" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: white; color: #334155; text-decoration: none; min-width: 40px; text-align: center; transition: all 0.2s;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'white\'">' . $i . '</a>';
            }
        }
        
        // Last page
        if ($end < $totalPages) {
            if ($end < $totalPages - 1) {
                $html .= '<span style="padding: 8px 4px; color: #94a3b8;">...</span>';
            }
            
            $queryParams[$pageParam] = $totalPages;
            $url = '?' . http_build_query($queryParams);
            $html .= '<a href="' . $url . '" class="pagination-number" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: white; color: #334155; text-decoration: none; min-width: 40px; text-align: center; transition: all 0.2s;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'white\'">' . $totalPages . '</a>';
        }
        
        $html .= '</div>';
        
        // Next button
        if ($currentPage < $totalPages) {
            $nextPage = $currentPage + 1;
            $queryParams[$pageParam] = $nextPage;
            $nextUrl = '?' . http_build_query($queryParams);
            $html .= '<a href="' . $nextUrl . '" class="pagination-btn" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: white; color: #334155; text-decoration: none; font-weight: 500; transition: all 0.2s;" onmouseover="this.style.background=\'#f1f5f9\'" onmouseout="this.style.background=\'white\'">Next →</a>';
        } else {
            $html .= '<span class="pagination-btn disabled" style="padding: 8px 12px; border: 1px solid #e2e8f0; border-radius: 6px; background: #f9fafb; color: #94a3b8; cursor: not-allowed;">Next →</span>';
        }
        
        $html .= '</nav>';
        $html .= '</div>';
        
        return $html;
    }
}

