<?php

namespace MediaWiki\Extension\PkgStore;

use MWException;
use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Src
 */
class MW_EXT_Src
{
  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setHook('src', [__CLASS__, 'onRenderTag']);
  }

  /**
   * Render tag function.
   *
   * @param $input
   * @param array $args
   * @param Parser $parser
   * @param PPFrame $frame
   *
   * @return string|null
   */
  public static function onRenderTag($input, array $args, Parser $parser, PPFrame $frame): ?string
  {
    // Message: block title.
    $msgTitle = MW_EXT_Kernel::getMessageText('src', 'block-title');

    // Argument: type.
    $getType = MW_EXT_Kernel::outClear($args['type'] ?? '' ?: 'block');
    $outType = $getType;

    // Argument: title.
    $getTitle = MW_EXT_Kernel::outClear($args['title'] ?? '' ?: $msgTitle);
    $outTitle = $getTitle;

    // Argument: lang.
    $getLang = MW_EXT_Kernel::outClear($args['lang'] ?? '' ?: 'none');
    $outLang = $getLang;

    // Get content.
    $getContent = MW_EXT_Kernel::outClear($input);
    $outContent = $getContent;

    // Out code class.
    $outClass = ' class="language-' . $outLang . '"';

    // Out HTML.
    if ($outType === 'block') {
      $outHTML = '<div class="mw-src mw-src-block navigation-not-searchable">';
      $outHTML .= '<div class="mw-src-header"><div class="mw-src-title">' . $outTitle . '</div><div class="mw-src-lang">' . $outLang . '</div></div>';
      $outHTML .= '<div class="mw-src-content"><pre><code' . $outClass . '>' . $outContent . '</code></pre></div>';
      $outHTML .= '</div>';
    } elseif ($outType === 'inline') {
      $outHTML = '<span class="mw-src mw-src-inline"><code' . $outClass . '>' . $outContent . '</code></span>';
    } else {
      $parser->addTrackingCategory('mw-src-error-category');

      return null;
    }

    // Out parser.
    return $outHTML;
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.src.styles']);
    $out->addModules(['ext.mw.src']);
  }
}
