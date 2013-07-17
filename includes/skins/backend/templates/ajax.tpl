
{foreach from=Logger::getListMessage() item="message"}

    <div class="alert">
        {$message->getMessage()}
    </div>

{foreachelse}
    {$controller->getPageContent()}
{/foreach}
