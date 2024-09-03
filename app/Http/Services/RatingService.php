<?php

namespace App\Http\Services;

use App\Models\Book;
use App\Models\BorrowRecord;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class RatingService {
    
    public function getAllRatings(){
        //جلب كل تقييمات المستخدم الخاصة به
        return Rating::where('user_id' , Auth::id())->get();
    }
    //========================================================================================================================
    public function createRating($data,$book_id){
        $id_user = Auth::id();
        //التحقق أولا من أن المستخدم قام باستعارة الكتاب أو لا , لكي يستطيع تقييم هذا الكتاب
        $check_borrow = BorrowRecord::where('user_id' , $id_user)->where('book_id', $book_id)->exists();
        if($check_borrow){
            $rating = Rating::create([
                'user_id' => $id_user,
                'book_id' => $book_id,
                'rating' => $data['rating'],
                'review' => $data['review'],
            ]);
            return $rating;
        }else{
            throw new \Exception("You cannot rating this book , because you do not borrow it.");
        }
    }

    //========================================================================================================================

    public function showRating($id){
        return Rating::findOrFail($id);
    }

    //========================================================================================================================

    public function updateRating($data,$id_rating){
        // التحقق مما إذا كان التقييم يعود إلى المستخدم الحالي
        $chack_rating_user = Rating::where('user_id', Auth::id())->where('id', $id_rating)->first();

        if ($chack_rating_user) {
            // تحديث التقييم إذا تم التحقق من ملكية المستخدم
            $chack_rating_user->update($data);
            return $chack_rating_user; // إعادة الكائن المحدّث
        } else {
            // رمي استثناء في حال عدم تطابق المستخدم مع التقييم
            throw new \Exception("You cannot update this rating it does not belongs to you.");
        }
    }

    //========================================================================================================================

       public function deleteRating($id_rating){
        $rating = Rating::find($id_rating);

        //التحقق في ما إذا كان التعليق يعود لنفس اليوزر
        $chack_rating_user = Rating::where('user_id' , Auth::id())->where('id' , $id_rating)->first();

        if($chack_rating_user){ 

            $rating->delete();
            return true;

        }else{
            throw new \Exception( "you can not delete this rating , do not referance to you");
        }
    }



    //========================================================================================================================
    //توابع خاصة بالأدمن ققططط


    public function getAllRatingsForAdmin(Request $request){
        // إنشاء كويري من الموديل لتتم معالجتها لاحقًا
        $query = Rating::query();

        // تطبيق الفلاتر بناءً على المدخلات من الطلب
        //فلترة حسب الكتاب لتظهر كل تقييمات كتاب معين لسهولة معرفة الكتاب الأعلى تقييماً
        if (!empty($request->book_id)) {
            $query->where('book_id', '=', $request->book_id);
        }
    
        // تنفيذ الكويري وجلب النتائج
        return $query->get();

      //  return Rating::all();
    }
    //========================================================================================================================

    public function deleteRatingForAdmin($id_rating){
        $rating = Rating::find($id_rating);

        $rating->delete();
        return true;
    }
}
