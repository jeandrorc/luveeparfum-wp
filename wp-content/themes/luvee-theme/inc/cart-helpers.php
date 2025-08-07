<?php
/**
 * Cart Helper Functions
 * Funções auxiliares para o sistema de carrinho
 */

// Prevenir acesso direto
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Formatar preço limpo sem HTML
 */
function luvee_format_price_clean($price)
{
  if (empty($price) || $price === '0' || $price === 0) {
    return 'R$ 0,00';
  }

  // Converter para string
  $clean_price = (string) $price;

  // Remover todas as tags HTML
  $clean_price = wp_strip_all_tags($clean_price);

  // Decodificar entidades HTML
  $clean_price = html_entity_decode($clean_price, ENT_QUOTES, 'UTF-8');

  // Remover espaços extras e caracteres especiais
  $clean_price = preg_replace('/\s+/', ' ', $clean_price);
  $clean_price = str_replace(['&nbsp;', '\u00a0'], ' ', $clean_price);
  $clean_price = trim($clean_price);

  // Se já tem formato correto, retornar
  if (preg_match('/^R\$\s*[\d,\.]+$/', $clean_price)) {
    return $clean_price;
  }

  // Extrair apenas números, vírgulas e pontos
  preg_match('/[\d,\.]+/', $clean_price, $matches);
  if (!empty($matches[0])) {
    $number_part = $matches[0];

    // Se tem vírgula como separador decimal
    if (strpos($number_part, ',') !== false) {
      return 'R$ ' . $number_part;
    }

    // Se é apenas números (centavos)
    if (is_numeric($number_part) && strlen($number_part) > 2) {
      $value = floatval($number_part) / 100;
      return 'R$ ' . number_format($value, 2, ',', '.');
    }

    // Fallback
    return 'R$ ' . $number_part;
  }

  return 'R$ 0,00';
}

/**
 * Obter total do carrinho formatado
 */
function luvee_get_cart_total_clean()
{
  if (!function_exists('WC') || !WC()->cart) {
    return 'R$ 0,00';
  }

  // Usar get_cart_total() que já vem formatado
  $total_html = WC()->cart->get_cart_total();
  return luvee_format_price_clean($total_html);
}

/**
 * Obter preço do produto formatado
 */
function luvee_get_product_price_clean($product)
{
  if (!$product || !is_object($product)) {
    return 'R$ 0,00';
  }

  // Usar WC()->cart->get_product_price() que já vem formatado
  $price_html = WC()->cart->get_product_price($product);
  return luvee_format_price_clean($price_html);
}

/**
 * Obter contagem do carrinho
 */
function luvee_get_cart_count()
{
  if (!function_exists('WC') || !WC()->cart) {
    return 0;
  }

  return WC()->cart->get_cart_contents_count();
}

/**
 * Verificar se o carrinho tem itens
 */
function luvee_cart_has_items()
{
  return luvee_get_cart_count() > 0;
}
?>