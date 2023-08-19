<?php

namespace App\Model;

use SilverStripe\ORM\DataObject;

class Location extends DataObject
{
    private static $db = [
        'Name' => 'Varchar',
        'City' => 'Varchar',
        'Lat' => 'Decimal',
        'Lng' => 'Decimal',
        'State' => 'Varchar'
    ];

    private static $summary_fields = [
        'ID' => 'ID',
        'City' => 'City',
        'State' => 'State'
    ];

    public function requireDefaultRecords()
    {
        parent::requireDefaultRecords(); // TODO: Change the autogenerated stub

        if (Location::get()->exists()) {
            return;
        }
        $locs = array(0 => array('city' => 'Karachi', 'lat' => '24.8600', 'lng' => '67.0100', 'admin_name' => 'Sindh',), 1 => array('city' => 'Lahore', 'lat' => '31.5497', 'lng' => '74.3436', 'admin_name' => 'Punjab',), 2 => array('city' => 'Faisalabad', 'lat' => '31.4180', 'lng' => '73.0790', 'admin_name' => 'Punjab',), 3 => array('city' => 'Rawalpindi', 'lat' => '33.6007', 'lng' => '73.0679', 'admin_name' => 'Punjab',), 4 => array('city' => 'Gujranwala', 'lat' => '32.1500', 'lng' => '74.1833', 'admin_name' => 'Punjab',), 5 => array('city' => 'Peshawar', 'lat' => '34.0000', 'lng' => '71.5000', 'admin_name' => 'Khyber Pakhtunkhwa',), 6 => array('city' => 'Multan', 'lat' => '30.1978', 'lng' => '71.4711', 'admin_name' => 'Punjab',), 7 => array('city' => 'Saidu Sharif', 'lat' => '34.7500', 'lng' => '72.3572', 'admin_name' => 'Khyber Pakhtunkhwa',), 8 => array('city' => 'Hyderabad City', 'lat' => '25.3792', 'lng' => '68.3683', 'admin_name' => 'Sindh',), 9 => array('city' => 'Islamabad', 'lat' => '33.6989', 'lng' => '73.0369', 'admin_name' => 'Islāmābād',), 10 => array('city' => 'Quetta', 'lat' => '30.1920', 'lng' => '67.0070', 'admin_name' => 'Balochistān',), 11 => array('city' => 'Bahawalpur', 'lat' => '29.3956', 'lng' => '71.6722', 'admin_name' => 'Punjab',), 12 => array('city' => 'Sargodha', 'lat' => '32.0836', 'lng' => '72.6711', 'admin_name' => 'Punjab',), 13 => array('city' => 'Sialkot City', 'lat' => '32.5000', 'lng' => '74.5333', 'admin_name' => 'Punjab',), 14 => array('city' => 'Sukkur', 'lat' => '27.6995', 'lng' => '68.8673', 'admin_name' => 'Sindh',), 15 => array('city' => 'Larkana', 'lat' => '27.5600', 'lng' => '68.2264', 'admin_name' => 'Sindh',), 16 => array('city' => 'Chiniot', 'lat' => '31.7167', 'lng' => '72.9833', 'admin_name' => 'Punjab',), 17 => array('city' => 'Shekhupura', 'lat' => '31.7083', 'lng' => '74.0000', 'admin_name' => 'Punjab',), 18 => array('city' => 'Jhang City', 'lat' => '31.2681', 'lng' => '72.3181', 'admin_name' => 'Punjab',), 19 => array('city' => 'Dera Ghazi Khan', 'lat' => '30.0500', 'lng' => '70.6333', 'admin_name' => 'Punjab',), 20 => array('city' => 'Gujrat', 'lat' => '32.5736', 'lng' => '74.0789', 'admin_name' => 'Punjab',), 21 => array('city' => 'Rahimyar Khan', 'lat' => '28.4202', 'lng' => '70.2952', 'admin_name' => 'Punjab',), 22 => array('city' => 'Kasur', 'lat' => '31.1167', 'lng' => '74.4500', 'admin_name' => 'Punjab',), 23 => array('city' => 'Mardan', 'lat' => '34.1958', 'lng' => '72.0447', 'admin_name' => 'Khyber Pakhtunkhwa',), 24 => array('city' => 'Mingaora', 'lat' => '34.7717', 'lng' => '72.3600', 'admin_name' => 'Khyber Pakhtunkhwa',), 25 => array('city' => 'Nawabshah', 'lat' => '26.2442', 'lng' => '68.4100', 'admin_name' => 'Sindh',), 26 => array('city' => 'Sahiwal', 'lat' => '30.6706', 'lng' => '73.1064', 'admin_name' => 'Punjab',), 27 => array('city' => 'Mirpur Khas', 'lat' => '25.5269', 'lng' => '69.0111', 'admin_name' => 'Sindh',), 28 => array('city' => 'Okara', 'lat' => '30.8100', 'lng' => '73.4597', 'admin_name' => 'Punjab',), 29 => array('city' => 'Mandi Burewala', 'lat' => '30.1500', 'lng' => '72.6833', 'admin_name' => 'Punjab',), 30 => array('city' => 'Jacobabad', 'lat' => '28.2769', 'lng' => '68.4514', 'admin_name' => 'Sindh',), 31 => array('city' => 'Saddiqabad', 'lat' => '28.3006', 'lng' => '70.1302', 'admin_name' => 'Punjab',), 32 => array('city' => 'Kohat', 'lat' => '33.5869', 'lng' => '71.4414', 'admin_name' => 'Khyber Pakhtunkhwa',), 33 => array('city' => 'Muridke', 'lat' => '31.8020', 'lng' => '74.2550', 'admin_name' => 'Punjab',), 34 => array('city' => 'Muzaffargarh', 'lat' => '30.0703', 'lng' => '71.1933', 'admin_name' => 'Punjab',), 35 => array('city' => 'Khanpur', 'lat' => '28.6471', 'lng' => '70.6620', 'admin_name' => 'Punjab',), 36 => array('city' => 'Gojra', 'lat' => '31.1500', 'lng' => '72.6833', 'admin_name' => 'Punjab',), 37 => array('city' => 'Mandi Bahauddin', 'lat' => '32.5861', 'lng' => '73.4917', 'admin_name' => 'Punjab',), 38 => array('city' => 'Abbottabad', 'lat' => '34.1500', 'lng' => '73.2167', 'admin_name' => 'Khyber Pakhtunkhwa',), 39 => array('city' => 'Turbat', 'lat' => '26.0031', 'lng' => '63.0544', 'admin_name' => 'Balochistān',), 40 => array('city' => 'Dadu', 'lat' => '26.7319', 'lng' => '67.7750', 'admin_name' => 'Sindh',), 41 => array('city' => 'Bahawalnagar', 'lat' => '29.9944', 'lng' => '73.2536', 'admin_name' => 'Punjab',), 42 => array('city' => 'Khuzdar', 'lat' => '27.8000', 'lng' => '66.6167', 'admin_name' => 'Balochistān',), 43 => array('city' => 'Pakpattan', 'lat' => '30.3500', 'lng' => '73.4000', 'admin_name' => 'Punjab',), 44 => array('city' => 'Tando Allahyar', 'lat' => '25.4667', 'lng' => '68.7167', 'admin_name' => 'Sindh',), 45 => array('city' => 'Ahmadpur East', 'lat' => '29.1453', 'lng' => '71.2617', 'admin_name' => 'Punjab',), 46 => array('city' => 'Vihari', 'lat' => '30.0419', 'lng' => '72.3528', 'admin_name' => 'Punjab',), 47 => array('city' => 'Jaranwala', 'lat' => '31.3342', 'lng' => '73.4194', 'admin_name' => 'Punjab',), 48 => array('city' => 'New Mirpur', 'lat' => '33.1333', 'lng' => '73.7500', 'admin_name' => 'Azad Kashmir',), 49 => array('city' => 'Kamalia', 'lat' => '30.7258', 'lng' => '72.6447', 'admin_name' => 'Punjab',), 50 => array('city' => 'Kot Addu', 'lat' => '30.4700', 'lng' => '70.9644', 'admin_name' => 'Punjab',), 51 => array('city' => 'Nowshera', 'lat' => '34.0153', 'lng' => '71.9747', 'admin_name' => 'Khyber Pakhtunkhwa',), 52 => array('city' => 'Swabi', 'lat' => '34.1167', 'lng' => '72.4667', 'admin_name' => 'Khyber Pakhtunkhwa',), 53 => array('city' => 'Khushab', 'lat' => '32.2917', 'lng' => '72.3500', 'admin_name' => 'Punjab',), 54 => array('city' => 'Dera Ismail Khan', 'lat' => '31.8167', 'lng' => '70.9167', 'admin_name' => 'Khyber Pakhtunkhwa',), 55 => array('city' => 'Chaman', 'lat' => '30.9210', 'lng' => '66.4597', 'admin_name' => 'Balochistān',), 56 => array('city' => 'Charsadda', 'lat' => '34.1453', 'lng' => '71.7308', 'admin_name' => 'Khyber Pakhtunkhwa',), 57 => array('city' => 'Kandhkot', 'lat' => '28.4000', 'lng' => '69.3000', 'admin_name' => 'Sindh',), 58 => array('city' => 'Chishtian', 'lat' => '29.7958', 'lng' => '72.8578', 'admin_name' => 'Punjab',), 59 => array('city' => 'Hasilpur', 'lat' => '29.6967', 'lng' => '72.5542', 'admin_name' => 'Punjab',), 60 => array('city' => 'Attock Khurd', 'lat' => '33.7667', 'lng' => '72.3667', 'admin_name' => 'Punjab',), 61 => array('city' => 'Muzaffarabad', 'lat' => '34.3700', 'lng' => '73.4711', 'admin_name' => 'Azad Kashmir',), 62 => array('city' => 'Mianwali', 'lat' => '32.5853', 'lng' => '71.5436', 'admin_name' => 'Punjab',), 63 => array('city' => 'Jalalpur Jattan', 'lat' => '32.7667', 'lng' => '74.2167', 'admin_name' => 'Punjab',), 64 => array('city' => 'Bhakkar', 'lat' => '31.6333', 'lng' => '71.0667', 'admin_name' => 'Punjab',), 65 => array('city' => 'Zhob', 'lat' => '31.3417', 'lng' => '69.4486', 'admin_name' => 'Balochistān',), 66 => array('city' => 'Dipalpur', 'lat' => '30.6708', 'lng' => '73.6533', 'admin_name' => 'Punjab',), 67 => array('city' => 'Kharian', 'lat' => '32.8110', 'lng' => '73.8650', 'admin_name' => 'Punjab',), 68 => array('city' => 'Mian Channun', 'lat' => '30.4397', 'lng' => '72.3544', 'admin_name' => 'Punjab',), 69 => array('city' => 'Bhalwal', 'lat' => '32.2653', 'lng' => '72.9028', 'admin_name' => 'Punjab',), 70 => array('city' => 'Jamshoro', 'lat' => '25.4283', 'lng' => '68.2822', 'admin_name' => 'Sindh',), 71 => array('city' => 'Pattoki', 'lat' => '31.0214', 'lng' => '73.8528', 'admin_name' => 'Punjab',), 72 => array('city' => 'Harunabad', 'lat' => '29.6100', 'lng' => '73.1361', 'admin_name' => 'Punjab',), 73 => array('city' => 'Kahror Pakka', 'lat' => '29.6236', 'lng' => '71.9167', 'admin_name' => 'Punjab',), 74 => array('city' => 'Toba Tek Singh', 'lat' => '30.9667', 'lng' => '72.4833', 'admin_name' => 'Punjab',), 75 => array('city' => 'Samundri', 'lat' => '31.0639', 'lng' => '72.9611', 'admin_name' => 'Punjab',), 76 => array('city' => 'Shakargarh', 'lat' => '32.2628', 'lng' => '75.1583', 'admin_name' => 'Punjab',), 77 => array('city' => 'Sambrial', 'lat' => '32.4750', 'lng' => '74.3522', 'admin_name' => 'Punjab',), 78 => array('city' => 'Shujaabad', 'lat' => '29.8803', 'lng' => '71.2950', 'admin_name' => 'Punjab',), 79 => array('city' => 'Hujra Shah Muqim', 'lat' => '30.7408', 'lng' => '73.8219', 'admin_name' => 'Punjab',), 80 => array('city' => 'Kabirwala', 'lat' => '30.4068', 'lng' => '71.8667', 'admin_name' => 'Punjab',), 81 => array('city' => 'Mansehra', 'lat' => '34.3333', 'lng' => '73.2000', 'admin_name' => 'Khyber Pakhtunkhwa',), 82 => array('city' => 'Lala Musa', 'lat' => '32.7006', 'lng' => '73.9558', 'admin_name' => 'Punjab',), 83 => array('city' => 'Chunian', 'lat' => '30.9639', 'lng' => '73.9803', 'admin_name' => 'Punjab',), 84 => array('city' => 'Nankana Sahib', 'lat' => '31.4492', 'lng' => '73.7124', 'admin_name' => 'Punjab',), 85 => array('city' => 'Bannu', 'lat' => '32.9889', 'lng' => '70.6056', 'admin_name' => 'Khyber Pakhtunkhwa',), 86 => array('city' => 'Pasrur', 'lat' => '32.2681', 'lng' => '74.6675', 'admin_name' => 'Punjab',), 87 => array('city' => 'Timargara', 'lat' => '34.8281', 'lng' => '71.8408', 'admin_name' => 'Khyber Pakhtunkhwa',), 88 => array('city' => 'Parachinar', 'lat' => '33.8992', 'lng' => '70.1008', 'admin_name' => 'Khyber Pakhtunkhwa',), 89 => array('city' => 'Chenab Nagar', 'lat' => '31.7500', 'lng' => '72.9167', 'admin_name' => 'Punjab',), 90 => array('city' => 'Gwadar', 'lat' => '25.1264', 'lng' => '62.3225', 'admin_name' => 'Balochistān',), 91 => array('city' => 'Abdul Hakim', 'lat' => '30.5522', 'lng' => '72.1278', 'admin_name' => 'Punjab',), 92 => array('city' => 'Hassan Abdal', 'lat' => '33.8195', 'lng' => '72.6890', 'admin_name' => 'Punjab',), 93 => array('city' => 'Tank', 'lat' => '32.2167', 'lng' => '70.3833', 'admin_name' => 'Khyber Pakhtunkhwa',), 94 => array('city' => 'Hangu', 'lat' => '33.5281', 'lng' => '71.0572', 'admin_name' => 'Khyber Pakhtunkhwa',), 95 => array('city' => 'Risalpur Cantonment', 'lat' => '34.0049', 'lng' => '71.9989', 'admin_name' => 'Khyber Pakhtunkhwa',), 96 => array('city' => 'Karak', 'lat' => '33.1167', 'lng' => '71.0833', 'admin_name' => 'Khyber Pakhtunkhwa',), 97 => array('city' => 'Kundian', 'lat' => '32.4522', 'lng' => '71.4718', 'admin_name' => 'Punjab',), 98 => array('city' => 'Umarkot', 'lat' => '25.3614', 'lng' => '69.7361', 'admin_name' => 'Sindh',), 99 => array('city' => 'Chitral', 'lat' => '35.8511', 'lng' => '71.7889', 'admin_name' => 'Khyber Pakhtunkhwa',), 100 => array('city' => 'Dainyor', 'lat' => '35.9206', 'lng' => '74.3783', 'admin_name' => 'Gilgit-Baltistan',), 101 => array('city' => 'Kulachi', 'lat' => '31.9286', 'lng' => '70.4592', 'admin_name' => 'Khyber Pakhtunkhwa',), 102 => array('city' => 'Kalat', 'lat' => '29.0258', 'lng' => '66.5900', 'admin_name' => 'Balochistān',), 103 => array('city' => 'Kotli', 'lat' => '33.5156', 'lng' => '73.9019', 'admin_name' => 'Azad Kashmir',), 104 => array('city' => 'Gilgit', 'lat' => '35.9208', 'lng' => '74.3144', 'admin_name' => 'Gilgit-Baltistan',), 105 => array('city' => 'Narowal', 'lat' => '32.1020', 'lng' => '74.8730', 'admin_name' => 'Punjab',), 106 => array('city' => 'Khairpur Mir’s', 'lat' => '27.5295', 'lng' => '68.7592', 'admin_name' => 'Sindh',), 107 => array('city' => 'Khanewal', 'lat' => '30.3017', 'lng' => '71.9321', 'admin_name' => 'Punjab',), 108 => array('city' => 'Jhelum', 'lat' => '32.9333', 'lng' => '73.7333', 'admin_name' => 'Punjab',), 109 => array('city' => 'Haripur', 'lat' => '33.9942', 'lng' => '72.9333', 'admin_name' => 'Khyber Pakhtunkhwa',), 110 => array('city' => 'Shikarpur', 'lat' => '27.9556', 'lng' => '68.6382', 'admin_name' => 'Sindh',), 111 => array('city' => 'Rawala Kot', 'lat' => '33.8578', 'lng' => '73.7604', 'admin_name' => 'Azad Kashmir',), 112 => array('city' => 'Hafizabad', 'lat' => '32.0709', 'lng' => '73.6880', 'admin_name' => 'Punjab',), 113 => array('city' => 'Lodhran', 'lat' => '29.5383', 'lng' => '71.6333', 'admin_name' => 'Punjab',), 114 => array('city' => 'Malakand', 'lat' => '34.5656', 'lng' => '71.9304', 'admin_name' => 'Khyber Pakhtunkhwa',), 115 => array('city' => 'Attock City', 'lat' => '33.7667', 'lng' => '72.3598', 'admin_name' => 'Punjab',), 116 => array('city' => 'Batgram', 'lat' => '34.6796', 'lng' => '73.0263', 'admin_name' => 'Khyber Pakhtunkhwa',), 117 => array('city' => 'Matiari', 'lat' => '25.5971', 'lng' => '68.4467', 'admin_name' => 'Sindh',), 118 => array('city' => 'Ghotki', 'lat' => '28.0064', 'lng' => '69.3150', 'admin_name' => 'Sindh',), 119 => array('city' => 'Naushahro Firoz', 'lat' => '26.8401', 'lng' => '68.1227', 'admin_name' => 'Sindh',), 120 => array('city' => 'Alpurai', 'lat' => '34.9000', 'lng' => '72.6556', 'admin_name' => 'Khyber Pakhtunkhwa',), 121 => array('city' => 'Bagh', 'lat' => '33.9803', 'lng' => '73.7747', 'admin_name' => 'Azad Kashmir',), 122 => array('city' => 'Daggar', 'lat' => '34.5111', 'lng' => '72.4844', 'admin_name' => 'Khyber Pakhtunkhwa',), 123 => array('city' => 'Leiah', 'lat' => '30.9646', 'lng' => '70.9444', 'admin_name' => 'Punjab',), 124 => array('city' => 'Tando Muhammad Khan', 'lat' => '25.1239', 'lng' => '68.5389', 'admin_name' => 'Sindh',), 125 => array('city' => 'Chakwal', 'lat' => '32.9300', 'lng' => '72.8500', 'admin_name' => 'Punjab',), 126 => array('city' => 'Badin', 'lat' => '24.6558', 'lng' => '68.8383', 'admin_name' => 'Sindh',), 127 => array('city' => 'Lakki', 'lat' => '32.6072', 'lng' => '70.9123', 'admin_name' => 'Khyber Pakhtunkhwa',), 128 => array('city' => 'Rajanpur', 'lat' => '29.1041', 'lng' => '70.3297', 'admin_name' => 'Punjab',), 129 => array('city' => 'Dera Allahyar', 'lat' => '28.4167', 'lng' => '68.1667', 'admin_name' => 'Balochistān',), 130 => array('city' => 'Shahdad Kot', 'lat' => '27.8473', 'lng' => '67.9068', 'admin_name' => 'Sindh',), 131 => array('city' => 'Pishin', 'lat' => '30.5833', 'lng' => '67.0000', 'admin_name' => 'Balochistān',), 132 => array('city' => 'Sanghar', 'lat' => '26.0464', 'lng' => '68.9481', 'admin_name' => 'Sindh',), 133 => array('city' => 'Upper Dir', 'lat' => '35.2074', 'lng' => '71.8768', 'admin_name' => 'Khyber Pakhtunkhwa',), 134 => array('city' => 'Thatta', 'lat' => '24.7461', 'lng' => '67.9243', 'admin_name' => 'Sindh',), 135 => array('city' => 'Dera Murad Jamali', 'lat' => '28.5466', 'lng' => '68.2231', 'admin_name' => 'Balochistān',), 136 => array('city' => 'Kohlu', 'lat' => '29.8965', 'lng' => '69.2532', 'admin_name' => 'Balochistān',), 137 => array('city' => 'Mastung', 'lat' => '29.7997', 'lng' => '66.8455', 'admin_name' => 'Balochistān',), 138 => array('city' => 'Dasu', 'lat' => '35.2917', 'lng' => '73.2906', 'admin_name' => 'Khyber Pakhtunkhwa',), 139 => array('city' => 'Athmuqam', 'lat' => '34.5717', 'lng' => '73.8972', 'admin_name' => 'Azad Kashmir',), 140 => array('city' => 'Loralai', 'lat' => '30.3705', 'lng' => '68.5979', 'admin_name' => 'Balochistān',), 141 => array('city' => 'Barkhan', 'lat' => '29.8977', 'lng' => '69.5256', 'admin_name' => 'Balochistān',), 142 => array('city' => 'Musa Khel Bazar', 'lat' => '30.8594', 'lng' => '69.8221', 'admin_name' => 'Balochistān',), 143 => array('city' => 'Ziarat', 'lat' => '30.3814', 'lng' => '67.7258', 'admin_name' => 'Balochistān',), 144 => array('city' => 'Gandava', 'lat' => '28.6132', 'lng' => '67.4856', 'admin_name' => 'Balochistān',), 145 => array('city' => 'Sibi', 'lat' => '29.5430', 'lng' => '67.8773', 'admin_name' => 'Balochistān',), 146 => array('city' => 'Dera Bugti', 'lat' => '29.0362', 'lng' => '69.1585', 'admin_name' => 'Balochistān',), 147 => array('city' => 'Eidgah', 'lat' => '35.3471', 'lng' => '74.8563', 'admin_name' => 'Gilgit-Baltistan',), 148 => array('city' => 'Uthal', 'lat' => '25.8072', 'lng' => '66.6228', 'admin_name' => 'Balochistān',), 149 => array('city' => 'Khuzdar', 'lat' => '27.7384', 'lng' => '66.6434', 'admin_name' => 'Balochistān',), 150 => array('city' => 'Chilas', 'lat' => '35.4206', 'lng' => '74.0967', 'admin_name' => 'Gilgit-Baltistan',), 151 => array('city' => 'Panjgur', 'lat' => '26.9644', 'lng' => '64.0903', 'admin_name' => 'Balochistān',), 152 => array('city' => 'Gakuch', 'lat' => '36.1736', 'lng' => '73.7667', 'admin_name' => 'Gilgit-Baltistan',), 153 => array('city' => 'Qila Saifullah', 'lat' => '30.7008', 'lng' => '68.3598', 'admin_name' => 'Balochistān',), 154 => array('city' => 'Kharan', 'lat' => '28.5833', 'lng' => '65.4167', 'admin_name' => 'Balochistān',), 155 => array('city' => 'Aliabad', 'lat' => '36.3078', 'lng' => '74.6178', 'admin_name' => 'Gilgit-Baltistan',), 156 => array('city' => 'Awaran', 'lat' => '26.4568', 'lng' => '65.2314', 'admin_name' => 'Balochistān',), 157 => array('city' => 'Dalbandin', 'lat' => '28.8885', 'lng' => '64.4062', 'admin_name' => 'Balochistān',),);

        foreach ($locs as $i => $val) {
            $dbObj = Location::create([
                'Name' => $val['city'],
                'City' => $val['city'],
                'Lat' => $val['lat'],
                'Lng' => $val['lng'],
                'State' => $val['admin_name'],
            ]);
            $dbObj->write();
        }
    }

}
