{if $product}
	<meta property="og:title" content="{$product.name|escape:'htmlall':'UTF-8'}"/>
    <meta property="og:type" content="product"/>
    <meta property="og:image" content="{$link->getImageLink($product.link_rewrite, $product.id_image, 'medium')}"/>
    <meta property="og:description" content="{$description|escape:htmlall:'UTF-8'}"/>
    <meta property="og:url" content="http://{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}"/>
{/if}