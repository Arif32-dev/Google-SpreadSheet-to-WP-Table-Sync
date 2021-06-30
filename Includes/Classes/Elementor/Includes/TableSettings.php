<?php

namespace GSWPTS_PRO\Includes\Classes\Elementor\Includes;

class TableSettings {

    /**
     * @return array
     */
    public function displaySettings(): array{
        $displaySettings = [
            'table_title'         => [
                'label'       => __('Table Title', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Enable this to show the table title in h3 tag above the table in your website front-end', 'sheetstowptable-pro'),
                'default'     => 'no'
            ],
            'rows_per_page'       => [
                'label'       => __('Default Rows Per Page', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'description' => __('This will show rows per page in the frontend', 'sheetstowptable-pro'),
                'options'     => $this->rowsPerPage()
            ],
            'info_block'          => [
                'label'       => __('Show Info Block', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Show Showing X to Y of Z entriesblock below the table', 'sheetstowptable-pro'),
                'default'     => 'yes'
            ],
            'responsive'          => [
                'label'       => __('Resposive Table', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Allow collapsing on mobile and tablet screen', 'sheetstowptable-pro'),
                'default'     => 'no'
            ],
            'show_entries'        => [
                'label'       => __('Show X Entries', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Show X entries per page dropdown', 'sheetstowptable-pro'),
                'default'     => 'yes'
            ],
            'swap_filter_inputs'  => [
                'label'       => __('Swap Filters', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Swap the places of X entries dropdown & search filter input', 'sheetstowptable-pro'),
                'default'     => 'no'
            ],
            'swap_bottom_options' => [
                'label'       => __('Swap Bottom Elements', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Swap the places of Showing X to Y of Z entries with table pagination filter', 'sheetstowptable-pro'),
                'default'     => 'no'
            ],
            'vertical_scrolling'  => [
                'label'       => __('Vertical Scroll/Sticky Header', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'description' => __('Choose the height of the table to scroll vertically. Activating this feature will allow the table to behave as sticky header', 'sheetstowptable-pro'),
                'options'     => $this->scrollHeightArray(),
                'default'     => 'default'
            ],
            'cell_format'         => [
                'label'       => __('Format Table Cell', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'description' => __('Format the table cell as like google sheet cell formatting. Format your cell as Wrap or Clip or Expanded style', 'sheetstowptable-pro'),
                'options'     => $this->cellFormattingArray()
            ],
            'redirection_type'    => [
                'label'       => __('Link Redirection Type', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'description' => __('Choose the redirection type of all the links in this table <br/>
                                    <b>Blank Type</b> = Opens the links in a new window or tab <br/>
                                    <b>Self Type</b> = Open links in the same tab (this is default)', 'sheetstowptable-pro'),
                'options'     => $this->redirectionTypeArray()
            ]
        ];

        return $displaySettings;
    }

    /**
     * @return mixed
     */
    public function sortFilterSettings(): array{
        $sortFilterSettings = [
            'sorting'      => [
                'label'       => __('Allow Sorting', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Enable this feature to sort table data for frontend.', 'sheetstowptable-pro'),
                'default'     => 'no'
            ],
            'search_table' => [
                'label'       => __('Search Bar', 'sheetstowptable-pro'),
                'type'        => \Elementor\Controls_Manager::SWITCHER,
                'description' => __('Enable this feature to show a search bar in for the table. It will help user to search data in the table', 'sheetstowptable-pro'),
                'default'     => 'no'
            ]
        ];

        return $sortFilterSettings;
    }

    /**
     * @return array
     */
    protected function redirectionTypeArray() {
        $options = [
            '_blank' => 'Blank Type',
            '_self'  => 'Self Type'
        ];

        return $options;
    }

    /**
     * @return array
     */
    protected function cellFormattingArray() {
        $options = [
            'clip'   => 'Clip Style',
            'wrap'   => 'Wrap Style',
            'expand' => 'Expanded Style'
        ];

        return $options;
    }

    /**
     * @return array
     */
    protected function scrollHeightArray() {
        $options = [
            '200'     => '200px',
            '400'     => '400px',
            '500'     => '500px',
            '600'     => '600px',
            '700'     => '700px',
            '800'     => '800px',
            '900'     => '900px',
            '1000'    => '1000px',
            'default' => 'Default Style'
        ];

        return $options;
    }

    /**
     * @return array
     */
    protected function rowsPerPage() {
        $options = [
            '1'   => '1',
            '5'   => '5',
            '10'  => '10',
            '15'  => '15',
            '25'  => '25',
            '50'  => '50',
            '100' => '100',
            'all' => 'All'
        ];

        return $options;
    }
}