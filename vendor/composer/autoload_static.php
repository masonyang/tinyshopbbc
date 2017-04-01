<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdcf5b5eefac29e9a58f92302b278a048
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Symfony\\Component\\OptionsResolver\\' => 34,
        ),
        'G' => 
        array (
            'Gaoming13\\WechatPhpSdk\\' => 23,
        ),
        'E' => 
        array (
            'Endroid\\QrCode\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Symfony\\Component\\OptionsResolver\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/options-resolver',
        ),
        'Gaoming13\\WechatPhpSdk\\' => 
        array (
            0 => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk',
        ),
        'Endroid\\QrCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/endroid/qrcode/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
    );

    public static $classMap = array (
        'Endroid\\QrCode\\Bundle\\Controller\\QrCodeController' => __DIR__ . '/..' . '/endroid/qrcode/src/Bundle/Controller/QrCodeController.php',
        'Endroid\\QrCode\\Bundle\\DependencyInjection\\Configuration' => __DIR__ . '/..' . '/endroid/qrcode/src/Bundle/DependencyInjection/Configuration.php',
        'Endroid\\QrCode\\Bundle\\DependencyInjection\\EndroidQrCodeExtension' => __DIR__ . '/..' . '/endroid/qrcode/src/Bundle/DependencyInjection/EndroidQrCodeExtension.php',
        'Endroid\\QrCode\\Bundle\\EndroidQrCodeBundle' => __DIR__ . '/..' . '/endroid/qrcode/src/Bundle/EndroidQrCodeBundle.php',
        'Endroid\\QrCode\\Bundle\\Twig\\Extension\\QrCodeExtension' => __DIR__ . '/..' . '/endroid/qrcode/src/Bundle/Twig/Extension/QrCodeExtension.php',
        'Endroid\\QrCode\\Exceptions\\DataDoesntExistsException' => __DIR__ . '/..' . '/endroid/qrcode/src/Exceptions/DataDoesntExistsException.php',
        'Endroid\\QrCode\\Exceptions\\FreeTypeLibraryMissingException' => __DIR__ . '/..' . '/endroid/qrcode/src/Exceptions/FreeTypeLibraryMissingException.php',
        'Endroid\\QrCode\\Exceptions\\ImageFunctionFailedException' => __DIR__ . '/..' . '/endroid/qrcode/src/Exceptions/ImageFunctionFailedException.php',
        'Endroid\\QrCode\\Exceptions\\ImageFunctionUnknownException' => __DIR__ . '/..' . '/endroid/qrcode/src/Exceptions/ImageFunctionUnknownException.php',
        'Endroid\\QrCode\\Exceptions\\ImageSizeTooLargeException' => __DIR__ . '/..' . '/endroid/qrcode/src/Exceptions/ImageSizeTooLargeException.php',
        'Endroid\\QrCode\\Exceptions\\ImageTypeInvalidException' => __DIR__ . '/..' . '/endroid/qrcode/src/Exceptions/ImageTypeInvalidException.php',
        'Endroid\\QrCode\\Exceptions\\VersionTooLargeException' => __DIR__ . '/..' . '/endroid/qrcode/src/Exceptions/VersionTooLargeException.php',
        'Endroid\\QrCode\\Factory\\QrCodeFactory' => __DIR__ . '/..' . '/endroid/qrcode/src/Factory/QrCodeFactory.php',
        'Endroid\\QrCode\\QrCode' => __DIR__ . '/..' . '/endroid/qrcode/src/QrCode.php',
        'Gaoming13\\WechatPhpSdk\\Api' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Api.class.php',
        'Gaoming13\\WechatPhpSdk\\Utils\\Error' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Utils/Error.class.php',
        'Gaoming13\\WechatPhpSdk\\Utils\\HttpCurl' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Utils/HttpCurl.class.php',
        'Gaoming13\\WechatPhpSdk\\Utils\\Pkcs7Encoder' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Utils/Pkcs7Encoder.class.php',
        'Gaoming13\\WechatPhpSdk\\Utils\\Prpcrypt' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Utils/Prpcrypt.class.php',
        'Gaoming13\\WechatPhpSdk\\Utils\\SHA1' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Utils/SHA1.class.php',
        'Gaoming13\\WechatPhpSdk\\Utils\\Xml' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Utils/Xml.class.php',
        'Gaoming13\\WechatPhpSdk\\Wechat' => __DIR__ . '/..' . '/gaoming13/wechat-php-sdk/src/WechatPhpSdk/Wechat.class.php',
        'PHPExcel' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel.php',
        'PHPExcel_Autoloader' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Autoloader.php',
        'PHPExcel_Best_Fit' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/trend/bestFitClass.php',
        'PHPExcel_CachedObjectStorageFactory' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorageFactory.php',
        'PHPExcel_CachedObjectStorage_APC' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/APC.php',
        'PHPExcel_CachedObjectStorage_CacheBase' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/CacheBase.php',
        'PHPExcel_CachedObjectStorage_DiscISAM' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/DiscISAM.php',
        'PHPExcel_CachedObjectStorage_ICache' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/ICache.php',
        'PHPExcel_CachedObjectStorage_Igbinary' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/Igbinary.php',
        'PHPExcel_CachedObjectStorage_Memcache' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/Memcache.php',
        'PHPExcel_CachedObjectStorage_Memory' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/Memory.php',
        'PHPExcel_CachedObjectStorage_MemoryGZip' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/MemoryGZip.php',
        'PHPExcel_CachedObjectStorage_MemorySerialized' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/MemorySerialized.php',
        'PHPExcel_CachedObjectStorage_PHPTemp' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/PHPTemp.php',
        'PHPExcel_CachedObjectStorage_SQLite' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/SQLite.php',
        'PHPExcel_CachedObjectStorage_SQLite3' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/SQLite3.php',
        'PHPExcel_CachedObjectStorage_Wincache' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CachedObjectStorage/Wincache.php',
        'PHPExcel_CalcEngine_CyclicReferenceStack' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CalcEngine/CyclicReferenceStack.php',
        'PHPExcel_CalcEngine_Logger' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/CalcEngine/Logger.php',
        'PHPExcel_Calculation' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation.php',
        'PHPExcel_Calculation_Database' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Database.php',
        'PHPExcel_Calculation_DateTime' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/DateTime.php',
        'PHPExcel_Calculation_Engineering' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Engineering.php',
        'PHPExcel_Calculation_Exception' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Exception.php',
        'PHPExcel_Calculation_ExceptionHandler' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/ExceptionHandler.php',
        'PHPExcel_Calculation_Financial' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Financial.php',
        'PHPExcel_Calculation_FormulaParser' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/FormulaParser.php',
        'PHPExcel_Calculation_FormulaToken' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/FormulaToken.php',
        'PHPExcel_Calculation_Function' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Function.php',
        'PHPExcel_Calculation_Functions' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Functions.php',
        'PHPExcel_Calculation_Logical' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Logical.php',
        'PHPExcel_Calculation_LookupRef' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/LookupRef.php',
        'PHPExcel_Calculation_MathTrig' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/MathTrig.php',
        'PHPExcel_Calculation_Statistical' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Statistical.php',
        'PHPExcel_Calculation_TextData' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/TextData.php',
        'PHPExcel_Calculation_Token_Stack' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Calculation/Token/Stack.php',
        'PHPExcel_Cell' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Cell.php',
        'PHPExcel_Cell_AdvancedValueBinder' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Cell/AdvancedValueBinder.php',
        'PHPExcel_Cell_DataType' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataType.php',
        'PHPExcel_Cell_DataValidation' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Cell/DataValidation.php',
        'PHPExcel_Cell_DefaultValueBinder' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Cell/DefaultValueBinder.php',
        'PHPExcel_Cell_Hyperlink' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Cell/Hyperlink.php',
        'PHPExcel_Cell_IValueBinder' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Cell/IValueBinder.php',
        'PHPExcel_Chart' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart.php',
        'PHPExcel_Chart_Axis' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/Axis.php',
        'PHPExcel_Chart_DataSeries' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/DataSeries.php',
        'PHPExcel_Chart_DataSeriesValues' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/DataSeriesValues.php',
        'PHPExcel_Chart_Exception' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/Exception.php',
        'PHPExcel_Chart_GridLines' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/GridLines.php',
        'PHPExcel_Chart_Layout' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/Layout.php',
        'PHPExcel_Chart_Legend' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/Legend.php',
        'PHPExcel_Chart_PlotArea' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/PlotArea.php',
        'PHPExcel_Chart_Renderer_jpgraph' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/Renderer/jpgraph.php',
        'PHPExcel_Chart_Title' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/Title.php',
        'PHPExcel_Comment' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Comment.php',
        'PHPExcel_DocumentProperties' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/DocumentProperties.php',
        'PHPExcel_DocumentSecurity' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/DocumentSecurity.php',
        'PHPExcel_Exception' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Exception.php',
        'PHPExcel_Exponential_Best_Fit' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/trend/exponentialBestFitClass.php',
        'PHPExcel_HashTable' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/HashTable.php',
        'PHPExcel_Helper_HTML' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Helper/HTML.php',
        'PHPExcel_IComparable' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/IComparable.php',
        'PHPExcel_IOFactory' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php',
        'PHPExcel_Linear_Best_Fit' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/trend/linearBestFitClass.php',
        'PHPExcel_Logarithmic_Best_Fit' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/trend/logarithmicBestFitClass.php',
        'PHPExcel_NamedRange' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/NamedRange.php',
        'PHPExcel_Polynomial_Best_Fit' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/trend/polynomialBestFitClass.php',
        'PHPExcel_Power_Best_Fit' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/trend/powerBestFitClass.php',
        'PHPExcel_Properties' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Chart/Properties.php',
        'PHPExcel_Reader_Abstract' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Abstract.php',
        'PHPExcel_Reader_CSV' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/CSV.php',
        'PHPExcel_Reader_DefaultReadFilter' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/DefaultReadFilter.php',
        'PHPExcel_Reader_Excel2003XML' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2003XML.php',
        'PHPExcel_Reader_Excel2007' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2007.php',
        'PHPExcel_Reader_Excel2007_Chart' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2007/Chart.php',
        'PHPExcel_Reader_Excel2007_Theme' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel2007/Theme.php',
        'PHPExcel_Reader_Excel5' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5.php',
        'PHPExcel_Reader_Excel5_Escher' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5/Escher.php',
        'PHPExcel_Reader_Excel5_MD5' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5/MD5.php',
        'PHPExcel_Reader_Excel5_RC4' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Excel5/RC4.php',
        'PHPExcel_Reader_Exception' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Exception.php',
        'PHPExcel_Reader_Gnumeric' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/Gnumeric.php',
        'PHPExcel_Reader_HTML' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/HTML.php',
        'PHPExcel_Reader_IReadFilter' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/IReadFilter.php',
        'PHPExcel_Reader_IReader' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/IReader.php',
        'PHPExcel_Reader_OOCalc' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/OOCalc.php',
        'PHPExcel_Reader_SYLK' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Reader/SYLK.php',
        'PHPExcel_ReferenceHelper' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/ReferenceHelper.php',
        'PHPExcel_RichText' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/RichText.php',
        'PHPExcel_RichText_ITextElement' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/RichText/ITextElement.php',
        'PHPExcel_RichText_Run' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/RichText/Run.php',
        'PHPExcel_RichText_TextElement' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/RichText/TextElement.php',
        'PHPExcel_Settings' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Settings.php',
        'PHPExcel_Shared_CodePage' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/CodePage.php',
        'PHPExcel_Shared_Date' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Date.php',
        'PHPExcel_Shared_Drawing' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Drawing.php',
        'PHPExcel_Shared_Escher' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher.php',
        'PHPExcel_Shared_Escher_DgContainer' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher/DgContainer.php',
        'PHPExcel_Shared_Escher_DgContainer_SpgrContainer' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher/DgContainer/SpgrContainer.php',
        'PHPExcel_Shared_Escher_DgContainer_SpgrContainer_SpContainer' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher/DgContainer/SpgrContainer/SpContainer.php',
        'PHPExcel_Shared_Escher_DggContainer' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher/DggContainer.php',
        'PHPExcel_Shared_Escher_DggContainer_BstoreContainer' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher/DggContainer/BstoreContainer.php',
        'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher/DggContainer/BstoreContainer/BSE.php',
        'PHPExcel_Shared_Escher_DggContainer_BstoreContainer_BSE_Blip' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Escher/DggContainer/BstoreContainer/BSE/Blip.php',
        'PHPExcel_Shared_Excel5' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Excel5.php',
        'PHPExcel_Shared_File' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/File.php',
        'PHPExcel_Shared_Font' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/Font.php',
        'PHPExcel_Shared_JAMA_LUDecomposition' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/JAMA/LUDecomposition.php',
        'PHPExcel_Shared_JAMA_Matrix' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/JAMA/Matrix.php',
        'PHPExcel_Shared_JAMA_QRDecomposition' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/JAMA/QRDecomposition.php',
        'PHPExcel_Shared_OLE' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/OLE.php',
        'PHPExcel_Shared_OLERead' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/OLERead.php',
        'PHPExcel_Shared_OLE_ChainedBlockStream' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/OLE/ChainedBlockStream.php',
        'PHPExcel_Shared_OLE_PPS' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/OLE/PPS.php',
        'PHPExcel_Shared_OLE_PPS_File' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/OLE/PPS/File.php',
        'PHPExcel_Shared_OLE_PPS_Root' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/OLE/PPS/Root.php',
        'PHPExcel_Shared_PasswordHasher' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/PasswordHasher.php',
        'PHPExcel_Shared_String' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/String.php',
        'PHPExcel_Shared_TimeZone' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/TimeZone.php',
        'PHPExcel_Shared_XMLWriter' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/XMLWriter.php',
        'PHPExcel_Shared_ZipArchive' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/ZipArchive.php',
        'PHPExcel_Shared_ZipStreamWrapper' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Shared/ZipStreamWrapper.php',
        'PHPExcel_Style' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style.php',
        'PHPExcel_Style_Alignment' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Alignment.php',
        'PHPExcel_Style_Border' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Border.php',
        'PHPExcel_Style_Borders' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Borders.php',
        'PHPExcel_Style_Color' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Color.php',
        'PHPExcel_Style_Conditional' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Conditional.php',
        'PHPExcel_Style_Fill' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Fill.php',
        'PHPExcel_Style_Font' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Font.php',
        'PHPExcel_Style_NumberFormat' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/NumberFormat.php',
        'PHPExcel_Style_Protection' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Protection.php',
        'PHPExcel_Style_Supervisor' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Style/Supervisor.php',
        'PHPExcel_Worksheet' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet.php',
        'PHPExcel_WorksheetIterator' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/WorksheetIterator.php',
        'PHPExcel_Worksheet_AutoFilter' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/AutoFilter.php',
        'PHPExcel_Worksheet_AutoFilter_Column' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/AutoFilter/Column.php',
        'PHPExcel_Worksheet_AutoFilter_Column_Rule' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/AutoFilter/Column/Rule.php',
        'PHPExcel_Worksheet_BaseDrawing' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/BaseDrawing.php',
        'PHPExcel_Worksheet_CellIterator' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/CellIterator.php',
        'PHPExcel_Worksheet_Column' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/Column.php',
        'PHPExcel_Worksheet_ColumnCellIterator' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/ColumnCellIterator.php',
        'PHPExcel_Worksheet_ColumnDimension' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/ColumnDimension.php',
        'PHPExcel_Worksheet_ColumnIterator' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/ColumnIterator.php',
        'PHPExcel_Worksheet_Drawing' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/Drawing.php',
        'PHPExcel_Worksheet_Drawing_Shadow' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/Drawing/Shadow.php',
        'PHPExcel_Worksheet_HeaderFooter' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/HeaderFooter.php',
        'PHPExcel_Worksheet_HeaderFooterDrawing' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/HeaderFooterDrawing.php',
        'PHPExcel_Worksheet_MemoryDrawing' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/MemoryDrawing.php',
        'PHPExcel_Worksheet_PageMargins' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/PageMargins.php',
        'PHPExcel_Worksheet_PageSetup' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/PageSetup.php',
        'PHPExcel_Worksheet_Protection' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/Protection.php',
        'PHPExcel_Worksheet_Row' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/Row.php',
        'PHPExcel_Worksheet_RowCellIterator' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/RowCellIterator.php',
        'PHPExcel_Worksheet_RowDimension' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/RowDimension.php',
        'PHPExcel_Worksheet_RowIterator' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/RowIterator.php',
        'PHPExcel_Worksheet_SheetView' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Worksheet/SheetView.php',
        'PHPExcel_Writer_Abstract' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Abstract.php',
        'PHPExcel_Writer_CSV' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/CSV.php',
        'PHPExcel_Writer_Excel2007' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007.php',
        'PHPExcel_Writer_Excel2007_Chart' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Chart.php',
        'PHPExcel_Writer_Excel2007_Comments' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Comments.php',
        'PHPExcel_Writer_Excel2007_ContentTypes' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/ContentTypes.php',
        'PHPExcel_Writer_Excel2007_DocProps' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/DocProps.php',
        'PHPExcel_Writer_Excel2007_Drawing' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Drawing.php',
        'PHPExcel_Writer_Excel2007_Rels' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Rels.php',
        'PHPExcel_Writer_Excel2007_RelsRibbon' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/RelsRibbon.php',
        'PHPExcel_Writer_Excel2007_RelsVBA' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/RelsVBA.php',
        'PHPExcel_Writer_Excel2007_StringTable' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/StringTable.php',
        'PHPExcel_Writer_Excel2007_Style' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Style.php',
        'PHPExcel_Writer_Excel2007_Theme' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Theme.php',
        'PHPExcel_Writer_Excel2007_Workbook' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Workbook.php',
        'PHPExcel_Writer_Excel2007_Worksheet' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/Worksheet.php',
        'PHPExcel_Writer_Excel2007_WriterPart' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel2007/WriterPart.php',
        'PHPExcel_Writer_Excel5' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5.php',
        'PHPExcel_Writer_Excel5_BIFFwriter' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/BIFFwriter.php',
        'PHPExcel_Writer_Excel5_Escher' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/Escher.php',
        'PHPExcel_Writer_Excel5_Font' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/Font.php',
        'PHPExcel_Writer_Excel5_Parser' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/Parser.php',
        'PHPExcel_Writer_Excel5_Workbook' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/Workbook.php',
        'PHPExcel_Writer_Excel5_Worksheet' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/Worksheet.php',
        'PHPExcel_Writer_Excel5_Xf' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Excel5/Xf.php',
        'PHPExcel_Writer_Exception' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/Exception.php',
        'PHPExcel_Writer_HTML' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/HTML.php',
        'PHPExcel_Writer_IWriter' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/IWriter.php',
        'PHPExcel_Writer_OpenDocument' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument.php',
        'PHPExcel_Writer_OpenDocument_Cell_Comment' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/Cell/Comment.php',
        'PHPExcel_Writer_OpenDocument_Content' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/Content.php',
        'PHPExcel_Writer_OpenDocument_Meta' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/Meta.php',
        'PHPExcel_Writer_OpenDocument_MetaInf' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/MetaInf.php',
        'PHPExcel_Writer_OpenDocument_Mimetype' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/Mimetype.php',
        'PHPExcel_Writer_OpenDocument_Settings' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/Settings.php',
        'PHPExcel_Writer_OpenDocument_Styles' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/Styles.php',
        'PHPExcel_Writer_OpenDocument_Thumbnails' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/Thumbnails.php',
        'PHPExcel_Writer_OpenDocument_WriterPart' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/OpenDocument/WriterPart.php',
        'PHPExcel_Writer_PDF' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF.php',
        'PHPExcel_Writer_PDF_Core' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF/Core.php',
        'PHPExcel_Writer_PDF_DomPDF' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF/DomPDF.php',
        'PHPExcel_Writer_PDF_mPDF' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF/mPDF.php',
        'PHPExcel_Writer_PDF_tcPDF' => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes/PHPExcel/Writer/PDF/tcPDF.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\AccessException' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/AccessException.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\ExceptionInterface' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/ExceptionInterface.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\InvalidArgumentException' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/InvalidArgumentException.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\InvalidOptionsException' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/InvalidOptionsException.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\MissingOptionsException' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/MissingOptionsException.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\NoSuchOptionException' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/NoSuchOptionException.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\OptionDefinitionException' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/OptionDefinitionException.php',
        'Symfony\\Component\\OptionsResolver\\Exception\\UndefinedOptionsException' => __DIR__ . '/..' . '/symfony/options-resolver/Exception/UndefinedOptionsException.php',
        'Symfony\\Component\\OptionsResolver\\Options' => __DIR__ . '/..' . '/symfony/options-resolver/Options.php',
        'Symfony\\Component\\OptionsResolver\\OptionsResolver' => __DIR__ . '/..' . '/symfony/options-resolver/OptionsResolver.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdcf5b5eefac29e9a58f92302b278a048::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdcf5b5eefac29e9a58f92302b278a048::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitdcf5b5eefac29e9a58f92302b278a048::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitdcf5b5eefac29e9a58f92302b278a048::$classMap;

        }, null, ClassLoader::class);
    }
}