<?php

if ( !defined( 'ABSPATH' ) ) die();

class YellowcoachescoukQuotes
{
    public function getQuoteHTML()
    {
        $YCWPDB = new YellowcoachescoukWPDB;
        $locations = $YCWPDB->getAllLocations();
        $location_html = '';
        $quote_html = '
            <div id="Yellowcoachescouk-quotes-container" class="container">
    
                <div class="row">
                    <div class="col">
                        <button type="button" class="yellowcoachescouk-dropbtn btn btn-primary">Origin</button>
                        <div id="Yellowcoachescouk-quote-dropdown-options-origin" class="yellowcoachescouk-dropdown-content">
                            <input class="yellowcoachescouk-quote-search" type="text" placeholder="Search here" />
        ';
    
        foreach ( $locations as $l )
        {
            $location_html .= '<button type="button" class="yellowcoachescouk-quote-anchor" value="' . $l->lid . '">' . $l->location . '</button>';
        }
    
        $quote_html .= $location_html;
        $quote_html .= '
                    </div>
                </div>
    
                <div class="col">
                    <button class="yellowcoachescouk-dropbtn btn btn-primary">Destination</button>
                    <div id="Yellowcoachescouk-quote-dropdown-options-destination" class="yellowcoachescouk-dropdown-content">
                        <input class="yellowcoachescouk-quote-search" type="text" placeholder="Search here" />
        ';
    
        $quote_html .= $location_html;
        $quote_html .= '
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col">
                        <h2>Origin</h2>
                        <h4 id="Yellowcoachescouk-quote-detail-origin-selection"></h4>
                    </div>
                    <div class="col">
                        <h2>Destination</h2>
                        <h4 id="Yellowcoachescouk-quote-detail-destination-selection"></h4>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col">
                        <h2>Cost</h2>
                        <h4 id="Yellowcoachescouk-quote-result"></h4>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col">
                        <button type="button" id="Yellowcoachescouk-quote-purchase" class="btn btn-primary">Purchase</button>
                    </div>
                </div>

            </div>
        ';
    
        return $quote_html;
    }

    public function getAdminProductsHTML()
    {
        $YCWPDB = new YellowcoachescoukWPDB;
        $locations = $YCWPDB->getAllLocations();
        $location_html = '';
        $quote_html = '
            <div id="Yellowcoachescouk-quotes-container" class="container">
    
                <div class="row">
                    <div class="col">
                        <button type="button" class="yellowcoachescouk-dropbtn btn btn-primary">Origin</button>
                        <div id="Yellowcoachescouk-quote-dropdown-options-origin" class="yellowcoachescouk-dropdown-content">
                            <input class="yellowcoachescouk-quote-search" type="text" placeholder="Search here" />
        ';
    
        foreach ( $locations as $l )
        {
            $location_html .= '<button type="button" class="yellowcoachescouk-quote-anchor" value="' . $l->lid . '">' . $l->location . '</button>';
        }
    
        $quote_html .= $location_html;
        $quote_html .= '
                    </div>
                </div>
    
                <div class="col">
                    <button class="yellowcoachescouk-dropbtn btn btn-primary">Destination</button>
                    <div id="Yellowcoachescouk-quote-dropdown-options-destination" class="yellowcoachescouk-dropdown-content">
                        <input class="yellowcoachescouk-quote-search" type="text" placeholder="Search here" />
        ';
    
        $quote_html .= $location_html;
        $quote_html .= '
                        </div>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col">
                        <h2>Origin</h2>
                        <h4 id="Yellowcoachescouk-quote-detail-origin-selection"></h4>
                    </div>
                    <div class="col">
                        <h2>Destination</h2>
                        <h4 id="Yellowcoachescouk-quote-detail-destination-selection"></h4>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col">
                        <h2>Cost</h2>
                        <input id="Yellowcoachescouk-admin-products-cost-input" type"text" placeholder="Quote cost..." />
                    </div>
                </div>
    
                <div class="row">
                    <div class="col">
                        <button type="button" id="Yellowcoachescouk-admin-products-save" class="btn btn-primary">Save Product</button>
                    </div>
                </div>

            </div>
        ';
    
        return $quote_html;
    }

    public function getAdminLocationSelectHTML()
    {
        $YCWPDB = new YellowcoachescoukWPDB;
        $location = $YCWPDB->getAllLocations();
        $location_html = '';

        $location_html .= '
            <button id="Yellowcoachescouk-admin-location-dropdown" class="yellowcoachescouk-dropbtn btn btn-primary" type="button">Location</button>
            <div id="Yellowcoachescouk-admin-dropdown-location-options" class="yellowcoachescouk-dropdown-content">
                <input class="yellowcoachescouk-quote-search" type="text" placeholder="Search here" />
        ';

        foreach ( $location as $l )
        {
            $location_html .= '<button type="button" class="yellowcoachescouk-quote-anchor-admin" value="' . $l->lid . '">' . $l->location . '</button>';
        }

        $location_html .= '</div>';

        return $location_html;
    }
}

?>