<?php

namespace Jogento\Keepfpc\Plugin\App\PageCache;

/**
 * Handles unique identifier for graphql query
 */
class Identifier
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    private $context;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serializer;

    /**
     * @var \Magento\PageCache\Model\Config
     */
    private $config;

    /**
     * @var \Jogento\Keepfpc\Helper\Data
     */
    private $helper;

    /**
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\App\Http\Context $context
     * @param \Magento\Framework\Serialize\Serializer\Json $serializer
     * @param \Magento\PageCache\Model\Config $config
     * @param \Jogento\Keepfpc\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\App\Http\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        \Magento\PageCache\Model\Config $config,
        \Jogento\Keepfpc\Helper\Data $helper
    ) {
        $this->request = $request;
        $this->context = $context;
        $this->serializer = $serializer;
        $this->config = $config;
        $this->helper = $helper;
    }

    /**
     * If at least one of the parameters is whitelisted, we remove them from the URI to generate the same cache id as the URI without parameters.
     *
     * @param \Magento\Framework\App\PageCache\Identifier $identifier
     * @param \Closure $proceed
     * @param string $result
     * @return string
     */
    public function aroundGetValue(\Magento\Framework\App\PageCache\Identifier $identifier, \Closure $proceed) : string
    {

        if(!$this->helper->isEnabled()){
            $result = $proceed();
        }else{
            $uristring = $this->helper->getUriStringWhitelisted($this->request);
            $data = [
                $this->request->isSecure(),
                $uristring,
                $this->request->get(\Magento\Framework\App\Response\Http::COOKIE_VARY_STRING)
                    ?: $this->context->getVaryString()
            ];
            $result = sha1($this->serializer->serialize($data));
        }

        return $result;
    }
}
