<?php

namespace NGFramer\NGFramerPHPSQLServices\Utilities;

use DateTime;
use Exception;
use InvalidArgumentException;

class ValueSanitizer
{
    public function sanitizeString(string $value): string
    {
        // Escape special characters in the string to prevent SQL injection.
        $escapedValue = addslashes($value);
        // Convert special characters to HTML entities for display safety (optional).
        return htmlspecialchars($escapedValue);
    }

    public function sanitizeInteger(int $value): int
    {
        // Ensure the input is an integer using intval.
        return intval($value);
    }

    /**
     * Escapes special LIKE pattern characters using the database's escape character.
     *
     * @param string $value The LIKE pattern string to escape
     * @return string The escaped LIKE pattern string
     * @throws Exception if no escape character is defined for the database
     */
    public function escapeLikePattern(string $value): string
    {
        // TODO: Define the database-specific escape character
        $escapeChar = '\\'; // Example: MySQL uses backslash

        // Escape the LIKE pattern special characters
        $escapedValue = str_replace(
            [$escapeChar, '%', '_'],
            [$escapeChar . $escapeChar, $escapeChar . '%', $escapeChar . '_'],
            $value
        );

        return $escapedValue;
    }

    /**
     * Validates and sanitizes a date/time string against a specified format.
     *
     * @param string $value The date/time string to sanitize
     * @param string $format The expected date/time format
     * @return string The sanitized date/time string
     * @throws InvalidArgumentException If the date/time string is invalid
     */
    public function sanitizeDateTime(string $value, string $format = 'Y-m-d H:i:s'): string
    {
        // Create a DateTime object from the input string and format
        $dateTime = DateTime::createFromFormat($format, $value);

        // Validate the DateTime object
        if (!$dateTime || $dateTime->format($format) !== $value) {
            throw new InvalidArgumentException("Invalid date/time format. Expected format: $format");
        }

        // Return the sanitized date/time string in the specified format
        return $dateTime->format($format);
    }

    /**
     * Sanitizes an array of values.
     *
     * @param array $values The array of values to sanitize
     * @param string $dataType The data type of the values ('string', 'integer', 'datetime', etc.)
     * @return array The sanitized array of values
     * @throws Exception if an unsupported data type is provided
     */
    public function sanitizeArray(array $values, string $dataType): array
    {
        $sanitizedValues = [];
        foreach ($values as $value) {
            $sanitizedValues[] = match ($dataType) {
                'string' => $this->sanitizeString($value),
                'integer' => $this->sanitizeInteger($value),
                'datetime' => $this->sanitizeDateTime($value),
                // ... add support for other data types as needed
                default => throw new InvalidArgumentException("Unsupported data type: $dataType"),
            };
        }
        return $sanitizedValues;
    }
}