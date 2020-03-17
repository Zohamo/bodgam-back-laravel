<?php

namespace App\Http\Middleware;

use Closure;

class TransformData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        $inputChanged = false;

        /**
         * Transform Timestamp inputs to String
         */

        $dateKeys = ['birthdate', 'startDatetime', 'endDatetime'];

        foreach ($dateKeys as $dateKey) {
            if (isset($input[$dateKey]) && $input[$dateKey] != null) {
                $input[$dateKey] = $this->timestampToString($input[$dateKey]);
                $inputChanged = true;
            }
        }

        /**
         * Get the foreign key from encapsuled objects
         */

        $foreignObjects = [
            [
                "object" => "location",
                "fk" => "locationId"
            ]
        ];

        foreach ($foreignObjects as $foreignObject) {
            if (isset($input[$foreignObject['object']]) && $input[$foreignObject['object']] != null) {
                $input[$foreignObject['fk']] = $input[$foreignObject['object']]['id'];
                unset($input[$foreignObject['object']]);
                $inputChanged = true;
            }
        }

        /**
         * Refresh the Request
         */

        if ($inputChanged) {
            $request->replace($input);
        }

        return $next($request);
    }

    /**
     * Transform a Timestamp into a String
     *
     * @param  int $timestamp
     * @return string
     */
    private function timestampToString(int $timestamp)
    {
        return gmdate("Y-m-d H:i", $timestamp / 1000); //TODO : report this front side
    }
}
