<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DirectorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('directors')->delete();
        
        \DB::table('directors')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '{"en":"Ruben Fleischer"}',
                'image' => 'tmdb_3JLxOPFTLigSy8FjFjDyMwD9GIp.jpg',
            'biography' => '{"en":"Ruben Samuel Fleischer (born October 31, 1974) is an American film director, film producer, television producer, music video director, and commercial director who lives in Los Angeles. He is best known as the director of Zombieland, his first feature film. He followed it by making the films 30 Minutes or Less, Gangster Squad and the 2018 film version of Venom which is based on the Marvel Comics character. Prior to directing feature films, Fleischer was a director of television commercials and music videos, working for such brands as Cisco, Eurostar, ESPN, and Burger King, as well as such artists as M.I.A., Electric Six, DJ Format, and Gold Chains."}',
                'place_of_birth' => ' Washington D.C., USA',
                'DOB' => '1974-10-31',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'ruben-fleischer',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '{"en":"Spiro Razatos"}',
                'image' => 'tmdb_tkJnvTnKa0t5HLi7dTKcXDrooFU.jpg',
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'spiro-razatos',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '{"en":"Dea Cantu"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'dea-cantu',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '{"en":"Mari Wilson"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'mari-wilson',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '{"en":"Diane Durant"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'diane-durant',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => '{"en":"C\\u00e9dric Jimenez"}',
                'image' => 'tmdb_1kYOeygLOQQ7ub3pQlp9U7gsb9M.jpg',
                'biography' => '{"en":""}',
                'place_of_birth' => 'Marseille, Bouches-du-Rhône, France',
                'DOB' => '1976-06-26',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'cedric-jimenez',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '{"en":"Takayuki Hamana"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => '1967-11-03',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'takayuki-hamana',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => '{"en":"Jaume Collet-Serra"}',
                'image' => 'tmdb_s1MdlS2wnzfKevPfXK63V1qkwfI.jpg',
            'biography' => '{"en":"Jaume Collet-Serra (born March 23, 1974 in Sant Iscle de Vallalta, Spain) is a Spanish film director and producer.\\n\\nCollet-Serra is most famous for the 2005 remake House of Wax, and 2009\'s Orphan. In 2010 he directed the Dark Castle drama-thriller Unknown which stars Frank Langella, Liam Neeson, January Jones and Diane Kruger.\\n\\nDescription above from the Wikipedia article Jaume Collet-Serra, licensed under CC-BY-SA, full list of contributors on Wikipedia."}',
                'place_of_birth' => 'Sant Iscle de Vallalta, Provinz Barcelona',
                'DOB' => '1974-03-23',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'jaume-collet-serra',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => '{"en":"Edgar Wright"}',
                'image' => 'tmdb_dQzv5Ml2SkelS33hgl7E2oh0a9S.jpg',
            'biography' => '{"en":"Edgar Howard Wright (born 18 April 1974) is an English director, screenwriter and producer.\\n\\nHe began making independent short films before making his first feature film \\"A Fistful of Fingers\\" (1995). Wright created and directed the comedy series \\"Asylum\\" in 1996, written with David Walliams. After directing several other television shows, Wright directed the sitcom \\"Spaced\\"(1999\\u20132001), which aired for two series and starred Simon Pegg and Nick Frost.\\n\\nIn 2004, Wright directed \\"Shaun of the Dead,\\" the first film in what would come to be called the Three Flavours Cornetto trilogy. \\"Shaun of the Dead\\" starred Pegg and Frost and was co-written with Pegg\\u2014as were the next two entries in the trilogy, \\"Hot Fuzz\\" (2007) and \\"The World\'s End\\" (2013). In 2010, Wright co-wrote, produced, and directed the comedy action film \\"Scott Pilgrim vs. the World.\\" Along with Joe Cornish and Steven Moffat, he co-wrote Steven Spielberg\'s \\"The Adventures of Tintin\\" (2011). After completing the Cornetto trilogy, Wright went on to direct the action film \\"Baby Driver\\" (2017) and the psychological thriller \\"Last Night in Soho\\" (2021).\\n\\nWright has directed numerous music videos, including The Bluetones\' \\"Keep the Home Fires Burning\\" (2000), The Eighties Matchbox B-Line Disaster\'s \\"Psychosis Safari\\" (2002), Mint Royale\'s \\"Blue Song\\" (2002), Pharrell Williams\' \\"Gust of Wind\\" (2014), and Beck\'s \\"Colors\\" (2018)."}',
                'place_of_birth' => 'Poole, Dorset, England, UK',
                'DOB' => '1974-04-18',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'edgar-wright',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => '{"en":"Paula Casarin"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'paula-casarin',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => '{"en":"Richard Graysmark"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'richard-graysmark',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => '{"en":"Lindsay Gossling"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'lindsay-gossling',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => '{"en":"Ruben Fleischer"}',
                'image' => 'tmdb_3JLxOPFTLigSy8FjFjDyMwD9GIp.jpg',
            'biography' => '{"en":"Ruben Samuel Fleischer (born October 31, 1974) is an American film director, film producer, television producer, music video director, and commercial director who lives in Los Angeles. He is best known as the director of Zombieland, his first feature film. He followed it by making the films 30 Minutes or Less, Gangster Squad and the 2018 film version of Venom which is based on the Marvel Comics character. Prior to directing feature films, Fleischer was a director of television commercials and music videos, working for such brands as Cisco, Eurostar, ESPN, and Burger King, as well as such artists as M.I.A., Electric Six, DJ Format, and Gold Chains."}',
                'place_of_birth' => ' Washington D.C., USA',
                'DOB' => '1974-10-31',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'ruben-fleischer',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => '{"en":"Spiro Razatos"}',
                'image' => 'tmdb_tkJnvTnKa0t5HLi7dTKcXDrooFU.jpg',
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'spiro-razatos',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => '{"en":"Dea Cantu"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'dea-cantu',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => '{"en":"Mari Wilson"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'mari-wilson',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => '{"en":"Diane Durant"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'diane-durant',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => '{"en":"Takayuki Hamana"}',
                'image' => NULL,
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => '1967-11-03',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'takayki-hamana',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => '{"en":"Ridley Scott"}',
                'image' => 'tmdb_zABJmN9opmqD4orWl3KSdCaSo7Q.jpg',
            'biography' => '{"en":"Ridley Scott was born on 30th November, 1937, in South Shields, Tyne and Wear, England, the son of Elizabeth and Colonel Francis Percy Scott. He was raised in an Army family, meaning that for most of his early life, his father \\u2014 an officer in the Royal Engineers \\u2014 was absent. Ridley\'s older brother, Frank, joined the Merchant Navy when he was still young and the pair had little contact. During this time the family moved around, living in (among other areas) Cumbria, Wales and Germany. He has a younger brother, Tony, also a film director. After the Second World War, the Scott family moved back to their native north-east England, eventually settling in Teesside (whose industrial landscape would later inspire similar scenes in Blade Runner). He enjoyed watching films, and his favourites include Lawrence of Arabia, Citizen Kane and Seven Samurai. Scott studied in Teesside from 1954 to 1958, at Grangefield Grammar School and later in West Hartlepool College of Art, graduating with a Diploma in Design. He progressed to an M.A. in graphic design at the Royal College of Art from 1960 to 1962.\\n\\nAt the RCA he contributed to the college magazine, ARK and helped to establish its film department. For his final show, he made a black and white short film, Boy and Bicycle, starring his younger brother, Tony Scott, and his father. The film\'s main visual elements would become features of Scott\'s later work; it was issued on the \'Extras\' section of The Duellists DVD. After graduation in 1963, he secured a job as a trainee set designer with the BBC, leading to work on the popular television police series Z-Cars and the science fiction series Out of the Unknown. Scott was an admirer of Stanley Kubrick early in his development as a director. For his entry to the BBC traineeship, Scott remade Paths of Glory as a short film.\\n\\nHe was assigned to design the second Doctor Who serial, The Daleks, which would have entailed realising the famous alien creatures. However, shortly before Scott was due to start work, a schedule conflict meant that he was replaced on the serial by Raymond Cusick.\\n\\nAt the BBC, Scott was placed into a director training programme and, before he left the corporation, had directed episodes of Z-Cars, its spin-off, Softly, Softly, and adventure series Adam Adamant Lives!\\n\\nIn 1968, Ridley and Tony Scott founded Ridley Scott Associates (RSA), a film and commercial production company.Five members of the Scott family are directors, all working for RSA. Brother Tony has been a successful film director for more than two decades; sons, Jake and Luke are both acclaimed commercials directors as is his daughter, Jordan Scott. Jake and Jordan both work from Los Angeles and Luke is based in London.\\n\\nIn 1995, Shepperton Studios was purchased by a consortium headed by Ridley and Tony Scott, which extensively renovated the studios while also expanding and improving its grounds. \\u00a0"}',
                'place_of_birth' => 'South Shields, County Durham, England, UK',
                'DOB' => '1937-11-30',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'ridley-scott',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => '{"en":"Wes Anderson"}',
                'image' => 'tmdb_oKDlhTjORiTQriqoUFJMTgGiwPg.jpg',
            'biography' => '{"en":"Wesley Wales Anderson (born May 1, 1969) is an American filmmaker. His films are known for their symmetry, eccentricity and distinctive visual and narrative styles, and he is cited by some critics as a modern-day example of the auteur. Three of his films, The Royal Tenenbaums (2001), Moonrise Kingdom (2012), and The Grand Budapest Hotel (2014) appeared in BBC Culture\'s 2016 poll of the greatest films since 2000.\\n\\nAnderson was nominated for the Academy Award for Best Original Screenplay for The Royal Tenenbaums (2001), Moonrise Kingdom (2012) and The Grand Budapest Hotel (2014), as well as the Academy Award for Best Animated Feature for the stop-motion films Fantastic Mr. Fox (2009) and Isle of Dogs (2018). With The Grand Budapest Hotel, he received his first Academy Award nominations for Best Director and Best Picture, and won the Golden Globe Award for Best Motion Picture \\u2013 Musical or Comedy and the BAFTA Award for Best Original Screenplay. He currently runs the production company American Empirical Pictures, which he founded in 1998. He won the Silver Bear for Best Director for Isle of Dogs in 2018"}',
                'place_of_birth' => 'Houston, Texas, USA',
                'DOB' => '1969-05-01',
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'wes-anderson',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => '{"en":"Ben Howard"}',
                'image' => 'tmdb_1SlABb5wF8iah78E4T8iB0tRFU8.jpg',
                'biography' => '{"en":""}',
                'place_of_birth' => NULL,
                'DOB' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
                'slug' => 'ben-howard',
            ),
        ));
        
        
    }
}