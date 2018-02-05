<?php

namespace Ivoz\Provider\Domain\Model\CallForwardSetting;

class CallForwardSettingDto extends CallForwardSettingDtoAbstract
{
    const CONTEXT_DETAILED_COLLECTION = 'detailedCollection';

    const CONTEXT_TYPES = [
        self::CONTEXT_COLLECTION,
        self::CONTEXT_SIMPLE,
        self::CONTEXT_DETAILED,
        self::CONTEXT_DETAILED_COLLECTION
    ];

    /**
     * @inheritdoc
     */
    public static function getPropertyMap(string $context = '')
    {
        if (in_array($context, [self::CONTEXT_COLLECTION, self::CONTEXT_DETAILED_COLLECTION])) {
            return [
                'callTypeFilter' => 'callTypeFilter',
                'callForwardType' => 'callForwardType',
                'targetType' => 'targetType',
                'numberValue' => 'numberValue',
                'noAnswerTimeout' => 'noAnswerTimeout',
                'id' => 'id',
                'userId' => 'user',
                'extensionId' => 'extension',
                'voiceMailUserId' => 'voiceMailUser',
                'numberCountryId' => 'numberCountry'
            ];
        }

        return parent::getPropertyMap($context);
    }

}


