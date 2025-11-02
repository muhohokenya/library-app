<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 100); //sample books
        $issued = $this->faker->numberBetween(0, min(20, $quantity));

        $bookTitles = [
            'The Great Gatsby',
            'To Kill a Mockingbird',
            '1984',
            'Pride and Prejudice',
            'The Catcher in the Rye',
            'Animal Farm',
            'Lord of the Flies',
            'Brave New World',
            'The Hobbit',
            'Fahrenheit 451',
            'Jane Eyre',
            'Wuthering Heights',
            'The Odyssey',
            'Crime and Punishment',
            'War and Peace',
        ];

        // Generate random location from A1 to Z9
        $letter = chr($this->faker->numberBetween(65, 90)); // A-Z (ASCII 65-90)
        $number = $this->faker->numberBetween(1, 9);
        $location = $letter . $number;

        return [
            'title' => $this->faker->randomElement($bookTitles) . ' - ' . $this->faker->word(),
            'author' => $this->faker->name(),
            'location' => $location,
            'isbn' => $this->faker->unique()->isbn13(),
            'description' => $this->faker->paragraph(3),
            'publisher' => $this->faker->company(),
            'published_year' => $this->faker->year(),
            'quantity' => $quantity,
            'available_quantity' => $quantity,
        ];
    }
}
