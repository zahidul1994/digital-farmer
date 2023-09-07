<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ApplicationStatus extends Enum
{
    const Pending = 0;
    const Fit = 1;
    const Unfit = 2;
    const Heldup = 3;
}