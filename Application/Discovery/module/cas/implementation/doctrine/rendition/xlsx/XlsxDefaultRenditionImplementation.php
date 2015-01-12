<?php
namespace Chamilo\Application\Discovery\Module\Cas\Implementation\Doctrine\Rendition\Xlsx;

class XlsxDefaultRenditionImplementation extends RenditionImplementation
{

    /**
     *
     * @var \PHPExcel
     */
    private $php_excel;

    public function render()
    {
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_format()
     */
    public function get_format()
    {
        return \Chamilo\Application\Discovery\Rendition :: FORMAT_XLSX;
    }

    /*
     * (non-PHPdoc) @see \application\discovery\AbstractRenditionImplementation::get_view()
     */
    public function get_view()
    {
        return \Chamilo\Application\Discovery\Rendition :: VIEW_DEFAULT;
    }
}
